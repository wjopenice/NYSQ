<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import { getNum } from '@/api/setting'
import echarts from 'echarts'
require('echarts/theme/macarons')
import resize from './mixins/resize'

export default {
  mixins: [resize],
  props: {
    className: {
      type: String,
      default: 'chart'
    },
    width: {
      type: String,
      default: '100%'
    },
    height: {
      type: String,
      default: '300px'
    }
  },
  data() {
    return {
      chart: null,
      listQuery: {},
      numData: {
        user: undefined,
        merc: undefined,
        shop: undefined,
        operator: undefined
      },
      listLoading: true
    }
  },
  created() {
    this.getList()
  },
  beforeDestroy() {
    if (!this.chart) {
      return
    }
    this.chart.dispose()
    this.chart = null
  },
  methods: {
    async getList() {
      this.listLoading = true
      await getNum(this.listQuery).then(response => {
        this.numData = response.data.items
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
      await this.initChart()
    },
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons')
      this.chart.setOption({
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        legend: {
          left: 'center',
          bottom: '10',
          data: ['user', 'merc', 'shop', 'operator']
        },
        series: [
          {
            name: 'WEEKLY WRITE ARTICLES',
            type: 'pie',
            roseType: 'radius',
            radius: [15, 95],
            center: ['50%', '38%'],
            data: [
              { value: this.numData.user, name: 'user' },
              { value: this.numData.merc, name: 'merc' },
              { value: this.numData.shop, name: 'shop' },
              { value: this.numData.operator, name: 'operator' }
            ],
            animationEasing: 'cubicInOut',
            animationDuration: 2600
          }
        ]
      })
    }
  }
}
</script>

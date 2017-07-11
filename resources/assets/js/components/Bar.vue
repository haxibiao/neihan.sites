
<script>
  import { Bar, mixins  } from 'vue-chartjs'
  const { reactiveProp } = mixins

  export default Bar.extend({
    mixins: [reactiveProp],
    props: ['chartData', 'options', 'title'],
    data () {
      return {
        datacollection: {
          labels: ['6天前','5天前','4天前','3天前','2天前','昨日', '今日'],
          datasets: [
            {
              label: this.title,
              backgroundColor: '#f87979',
              data: eval(this.chartData)
            }
          ]
        }
      }
    },
    mounted () {
      // this.fillData();
      this.renderChart(this.datacollection);
    },
    methods: {
      fillData () {
        this.datacollection = {
          labels: [this.getRandomInt(), this.getRandomInt()],
          datasets: [
            {
              label: 'Data One',
              backgroundColor: '#f87979',
              data: [this.getRandomInt(), this.getRandomInt()]
            }, {
              label: 'Data One',
              backgroundColor: '#f87979',
              data: [this.getRandomInt(), this.getRandomInt()]
            }
          ]
        }
      },
      getRandomInt () {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      }
    }
  })
</script>
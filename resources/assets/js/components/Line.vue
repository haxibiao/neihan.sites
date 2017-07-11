
<script>
  import { Line, mixins  } from 'vue-chartjs'
  const { reactiveProp } = mixins

  export default Line.extend({
    mixins: [reactiveProp],
    props: ['chartLabels','chartData', 'options', 'title'],
    data () {
      return {
        datacollection: {
          labels: eval(this.chartLabels),
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
        
      }
    }
  })
</script>
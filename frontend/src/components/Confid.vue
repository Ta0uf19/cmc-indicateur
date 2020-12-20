<template>
  <div class="container">
    <strong>Indicateur 2  : Confiance en soi des étudiants </strong>
    <p class="font-italic">Représente la confiance en soi des étudiants dans le temps, l'indicateur permet alors de mieux intégrer les étudiants aux cours et les pousser à participer afin de prendre en compte les difficultés et les acquis de tous les étudiants pendant,avant et après le cours</p>
    <div v-if="loading" class="row spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    <apexchart v-else :options="options" :series="series"></apexchart>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: "Confid",
  data() {
    return {
      loading: false,
      series:[
        {
          name: 'TEAM A',
          type: 'column',
          data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
        }, {
          name: 'TEAM B',
          type: 'area',
          data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
        }, {
          name: 'Avg',
          type: 'line',
          data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
        }
      ],
      options: {
        chart: {
          height: 350,
          type: 'line',
          stacked: false,
        },
        stroke: {
          width: [0, 2, 5],
          curve: 'smooth'
        },
        plotOptions: {
          bar: {
            columnWidth: '50%'
          }
        },
        fill: {
          colors: ['#0789c8', '#77429d', '#E91E63'],
          opacity: [0.85, 1, 1],
        },
        labels: ['01/01/2003', '02/01/2003', '03/01/2003', '04/01/2003', '05/01/2003', '06/01/2003', '07/01/2003', '08/01/2003', '09/01/2003', '10/01/2003', '11/01/2003'],
        markers: {
          size: 0
        },
        xaxis: {
          type: 'string'
        },
        yaxis: {
          title: {
            text: 'Score confiance',
          },
          min: 0
        },
        tooltip: {
          shared: true,
          intersect: false,
          y: {
            formatter: function (y) {
              if (typeof y !== "undefined") {
                return y.toFixed(0) + " points";
              }
              return y;

            }
          }
        },
      }
    }
  },
  methods: {
    async getConfidenceData() {
      this.loading = true;

      let users = []
      let avg = [];
      let scores = [];

      await axios
          .get("http://localhost:8000/confidence")
          .then(({data}) => {
            // format productivity data
            console.log(data);

            data.data.forEach(elem => {
              users.push(elem.user)
              scores.push(elem.total)
              avg.push(data.avg_confidence)
            })

            console.log(avg);
            console.log(scores);
            console.log(users);
            this.loading = false
          });


      return {
        series: [
          {
            name: 'Score confiance',
            type: 'column',
            data: scores
          },
          {
            name: 'Score moyenne confiance',
            type: 'line',
            data: avg
          }
        ],
        users
      }
    }
  },
  async mounted() {
    // get productivity data
    let confidenceData = await this.getConfidenceData();
    // update graph
    this.series = confidenceData.series
    this.options = {labels: confidenceData.users }

  }
}
</script>

<style scoped>

</style>

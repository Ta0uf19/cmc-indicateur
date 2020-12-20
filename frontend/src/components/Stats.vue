<template>
  <div class="container">
    <strong>Indicateur 1  : Productivité des étudiants</strong>
    <p>Permet d'afficher la productivité (consultation des cours..) des étudiants classé par date et ansi que le pourcentage des étudiants impliqués, l'indicateur va permettre au professeur de mieux répartir la charge de travail aux étudiants et d'adapter son cours pour le rendre plus dynamique </p>

    <div v-if="loading" class="spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    <apexchart v-else :options="options" :series="series"></apexchart>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Stats',
  data() {
    return {
      loading: true,
      productivity: [],
      series: [
        {
          name: 'Website Blog',
          type: 'column',
          data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
        },
        {
          name: 'Social Media',
          type: 'line',
          data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
        }
      ],
      options: {
        chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Productivité des étudiants'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
        xaxis: {
          type: 'datetime'
        },
        yaxis: [{
          title: {
            text: 'Pourcentage d\'implication (%)',
          },

        }, {
          floating: false,
          opposite: true,
          title: {
            text: 'Total interactions'
          }
        }
        ]
      }
    }
  },
  methods: {
    /**
     * Get productivity data
     */
    async getProductivityData() {

      //start loading
      this.loading = true;

      let activities = []
      let rates = []
      let dates = [];

      await axios
          .get("http://localhost:8000/productivity")
          .then(({data}) => {
            // format productivity data
            console.log(data);

            data.forEach(elem => {
              activities.push(Math.trunc(elem.activity_total))
              rates.push(elem.participation_rate)
              dates.push(elem.date)
            });

            this.loading = false
          });

      // console.log(activities);
      // console.log(rates);

      return {
        series: [
          {
            name: 'Pourcentage d\'implication',
            type: 'column',
            data: rates
          },
          {
            name: 'Total interactions',
            type: 'line',
            data: activities
          }
        ],
        dates
      }
    },
  },
  async mounted() {
    // get productivity data
    let productivityData = await this.getProductivityData();
    // update graph
    this.series = productivityData.series
    this.options = {labels: productivityData.dates }
  }
}
</script>

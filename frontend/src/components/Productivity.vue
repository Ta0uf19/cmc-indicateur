<template>
  <div class="container">
    <p class="font-weight-light font-italic">Vivien Kouamedjo  & Taoufik Tribki</p> <br/>
    <strong>Indicateur 1  : Productivité des étudiants</strong>
    <p class="font-italic">Permet d'afficher la productivité (consultation des cours/forums..) des étudiants classés par date et ainsi que le pourcentage des étudiants impliqués, l'indicateur permet aux professeurs de mieux répartir la charge de travail aux étudiants et d'adapter son cours pour le rendre plus dynamique et interactive</p>

    <div v-if="loading" class="row spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    <apexchart v-else :options="options" :series="series"></apexchart>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Productivity',
  data() {
    return {
      loading: true,
      loadingc: false,
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
        labels: [],
        xaxis: {
          type: 'datetime',
          labels: {
            format: 'dd MMM yyyy',
          }
        },
        yaxis: [
          {
            title: {
              text: 'Pourcentage d\'implication (%)',
            },
            labels: {
              formatter: function (val) {
                return val + "%"
              },
            }
          },
          {
            floating: false,
            opposite: true,
            title: {
              text: 'Total interactions'
            }
          }
        ]
      },

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
              activities.push(elem.activity_total)
              rates.push(Math.floor(elem.participation_rate*100))
              dates.push(elem.date)
            });

            this.loading = false
          });

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

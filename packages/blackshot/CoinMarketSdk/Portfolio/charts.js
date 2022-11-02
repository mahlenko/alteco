/* https://apexcharts.com */
import ApexCharts from 'apexcharts'

// let chartContainer = document.querySelector("#chart")
document.addEventListener("DOMContentLoaded", function (event) {
  document.querySelectorAll('[data-chart]').forEach(container => {
    charts.area(container)
  })
})


window.charts = {
  area: (container) => {
    let chart = new ApexCharts(container, {
      chart: { id: container.id, height: 320, type: "area", toolbar: false},
      stroke: {width: 2},
      noData: {text: 'Пожалуйста подождите...', style: { color: 'slategray' }},
      series: [{ name: 'Баланс', data: [] }],
      dataLabels: {'enabled': false},
      xaxis: {labels: {show: false}, tooltip: {enabled: false}},
      yaxis: {
        labels: {
          formatter: (value) => {
            let count = value.toString().split('.').map(e => e.length)

            if (value < 1) {
              value = value.toString().substring(0, count[0] + 6)
            } else {
              value = value.toFixed(2)
            }

            return '$'+ value
          }
        }
      },
      responsive: [
        {'breakpoint': 769, 'options': {'chart': {'height': 200} }}
      ],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.4,
          opacityTo: 0.2,
          stops: [0, 100]
        }
      },
    });

    chart.render();

    charts.loadData(container, container.dataset.chart)
  },

  loadData: (container, url) => {
    container.style.position = 'relative'

    let loaderContainer = document.createElement('div')
    loaderContainer.style.backgroundColor = 'rgba(225,225,225,.5)'
    loaderContainer.style['backdrop-filter'] = 'blur(2px)'
    loaderContainer.style.borderRadius = '1rem'
    loaderContainer.style.display = 'flex'
    loaderContainer.style.alignItems = 'center'
    loaderContainer.style.justifyContent = 'center'
    loaderContainer.style.width = '100%'
    loaderContainer.style.height = '100%'
    loaderContainer.style.top = 0
    loaderContainer.style.position = 'absolute'
    loaderContainer.style.padding = '1rem'
    loaderContainer.style.transition = 'all 1s ease-in-out'

    // loaderContainer.appendChild(loader)
    container.appendChild(loaderContainer)

    axios.get(url)
      .then(result => {
        if (!result.data.ok) {
          ApexCharts.exec(container.id, 'updateOptions', {
            noData: {text: result.data.message ?? undefined}
          })
          return
        }

        ApexCharts.exec(container.id, 'updateOptions', result.data.data)

        loaderContainer.remove()
      }).catch(error => {
        console.log(error.response.statusText)

        ApexCharts.exec(container.id, 'updateOptions', {
          noData: {text: 'Произошла ошибка. Пожалуйста, обновите страницу или попробуйте позже.'}
        })
    })
  },
}

// let options = {
//   chart: { height: 320, type: "area", toolbar: false},
//   colors: [chartContainer.dataset.color ?? '#2E93fA'],
//   responsive: [ { breakpoint: 768, options: { chart: {height: 200} } } ],
//   stroke: { width: 2 },
//   dataLabels: { enabled: false },
//
//   series: [
//     {
//       name: "Price",
//       data: [45, 52, 38, 45, 19, 23, 2],
//     }
//   ],
//
//   fill: {
//     type: "gradient",
//     gradient: {
//       shadeIntensity: 1,
//       opacityFrom: 0.4,
//       opacityTo: 0.2,
//       stops: [0, 100]
//     }
//   },
//
//   xaxis: {
//     categories: [ "01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan"]
//   }
// }

// let optionsPie = {
//   chart: {
//     height: 320,
//     type: "donut",
//   },
//   series: [44, 55, 13, 33],
//   labels: ['Apple', 'Mango', 'Orange', 'Watermelon'],
//   dataLabels: {
//     enabled: false
//   },
//   legend: {
//     show: true,
//     position: 'bottom',
//     formatter: function(val) {
//       return '12 ' + val +' 43'
//     }
//   },
//   plotOptions: {
//     pie: {
//       customScale: 0.8,
//       donut: {
//         size: '70%',
//         labels: {
//           show: true
//         }
//       }
//     },
//   },
// }


// let chart = new ApexCharts(chartContainer, options);
// chart.render();

// let chartPie = new ApexCharts(document.querySelector('#chart-pie'), optionsPie);
// chartPie.render();

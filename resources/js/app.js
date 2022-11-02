require('./bootstrap');
require('bootstrap/js/dist/dropdown')

import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import animated from "@amcharts/amcharts5/themes/Animated";
import IMask from 'imask';

document.addEventListener("DOMContentLoaded", function (event) {
  // диалоговые окна
  Dialog.registerShortcut()

  Modal.register(document.querySelectorAll('[data-modal]'))

  // графики
  let graphs = document.querySelectorAll('[data-graph-json]')
  graphs.forEach(graph => {
    createGraph(graph, graph.dataset.reverse)
  })

  // табы
  let tabs = document.querySelectorAll('.tabs')
  tabs.forEach(tab => {
    Tabs.init(tab)
  })

  // "покупаю"
  let btn_buying = document.querySelectorAll('a[data-buying]');
  if (btn_buying) {
    btn_buying.forEach(btn => {
      btn.addEventListener('click', function () {
        return buying(this.dataset.uuid)
      })
    })
  }

  // мобильный просмотр таблиц
  let tables = document.querySelectorAll('table.table')
  if (tables.length) {
    tables.forEach(table => {
      if (table.querySelector('thead')) {
        mobileTable(table)
      }
    })
  }

  if ($('select[multiple]').length) {
    $('select[multiple]').selectize({
      render: {
        option: function (item, escape) {
          // кнопка подписаться в выпадающем списке
          if (item.value === 'subscribe') {
            return '<a href="' + item.url + '" class="subscribe">' + item.text + '</a>';
          }

          return '<div data-value="' + item.value + '" class="option">' + escape(item.text) + '</div>';
        },
      },

      onItemAdd: function (value) {
        if (value !== 'subscribe') {
          return
        }

        // убираем из выбранных элементов subscribe
        let values = []
        let currentValues = this.getValue()
        let subscribeIndex = currentValues.indexOf('subscribe')

        if (subscribeIndex !== -1) {
          if (currentValues.length > 0) {
            currentValues.forEach((val, index) => {
              if (subscribeIndex !== index) {
                values.push(val)
              }
            })
            this.setValue(values, true)
          }
        }

        this.close()
        this.refreshOptions()

        // перенаправляем пользователя по ссылке subscribe
        let subscribeLink = this.$dropdown[0].querySelector('.subscribe')
        document.location.href = subscribeLink.href

        // очищаем весь список
        // clearOptions(true)
      },
    });
  }

  // очистка выбранных элементов выпадающего списка
  document.querySelectorAll('[data-clear-for]').forEach(button => {
    button.addEventListener('click', e => {
      e.preventDefault()
      let id = button.dataset.clearFor
      let select = document.querySelector('#'+ id)
      select.selectize.clear()
    })
  })

  // анимация рейтинга alteco
  stepsProgress(document.querySelectorAll('.step-progress'))

  // анимация чисел
  numberAnimation(document.querySelectorAll('[data-counter-step]'))

});

const Modal = {
  register: items => {
    if (!items || !items.length) return true

    // Предварительно загрузим лоадер, /css/arcticmodal/loading.gif
    let loader = document.createElement('img')
    loader.src = '/css/arcticmodal/loading.gif'

    // Клики для открытия модальных окон
    items.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault()

        $.arcticmodal({
          type: 'ajax',
          url: item.dataset.modal == "" ? item.href : item.dataset.modal,
          openEffect: {speed: 200},
          closeEffect: {speed: 200},
          afterLoadingOnShow: (data, el) => {
            Modal.loaded($('div.box-modal'))
          },
          ajax: {
            type: 'GET',
          },
          overlay: {
            css: {
              backgroundColor: 'rgba(10,30,66,.4)',
              opacity: 1
            }
          }
        });
      })
    })
  },

  errorMessage: (container, response) => {
    let $container = container.find('[data-id="message"]')
    if (!$container.length) {
      return false;
    }

    let message = []

    if (response.message) {
      message.push('<p><b>'+ response.message +'</b></p>')
    }

    if (response.errors) {
      message.push('<ul>')
      Object.keys(response.errors).forEach(field => {
        message.push('<li>'+ response.errors[field].join(',') +'</li>')
      })
      message.push('</ul>')
    }

    $container.html(message.join("\n"))
    $container.slideDown(200)

    // setTimeout(() => {
    //   $container.slideUp(200)
    //   $container.html('')
    // }, 5000)
  },

  loaded: $container => {
    Modal.handleForms($container)

    let container = $container[0]
    let selectorSelectize = 'select[data-ui="selectize"]'

    // dropdowns
    if (container.querySelectorAll(selectorSelectize).length) {
      container.querySelectorAll(selectorSelectize).forEach(select => {
        // get options
        let options = []
        select.querySelectorAll('option').forEach(option => {
          let dataOption = {
            text: option.dataset.text ?? option.textContent.trim(),
            value: option.value,
            disabled: option.disabled,
            data: option.dataset,
          }
          if (option.dataset.order) {
            dataOption.order = option.dataset.order
          }

          options.push(dataOption)
        })

        $(select).selectize({
          options: options,
          sortField: options ? (options[0].order ? 'order' : '$order') : '$order',
          render: {
            item: function (item, escape) {
              let icon = ''
              if (item.data.icon) {
                icon = '<span class="icon-wrap">' +
                  '<img src="'+ item.data.icon +'" class="icon" alt="'+ item.text +'"/>' +
                  '</span>'
              }

              return '<div class="selected">'+ icon + '<span class="caption">'+ item.text +'</span>' +'</div>';
            },
            option: (item, escape) => {
              let icon = '<span class="icon-wrap"></span>'
              if (item.data.icon) {
                icon = '<span class="icon-wrap">' +
                  '<img src="'+ item.data.icon +'" class="icon" alt="'+ item.text +'"/>' +
                  '</span>'
              }

              return '<div class="brand__option">'+ icon + '<span class="caption">'+ item.text +'</span>' +'</div>'
            }
          }
        })

      })
    }

    // numbers imask
    if (container.querySelectorAll('input[data-type="number"]')) {
      container.querySelectorAll('input[data-type="number"]').forEach(input => {
        let mask = IMask(input, {
          mask: Number,
          scale: 8,
          signed: true,
        });

        input.addEventListener('updateMask', () => {
          mask.value = input.value
        })
      })
    }

    // price
    if (container.querySelectorAll('input[data-type="price"]')) {
      container.querySelectorAll('input[data-type="price"]').forEach(input => {
        let mask = IMask(input, {
          mask: '$num',
          blocks: {
            num: {
              // nested masks are available!
              mask: Number,
              scale: 8,
              signed: true,
              thousandsSeparator: ' '
            }
          }
        })

        input.addEventListener('updateMask', () => {
          mask.value = input.value
        })
      })
    }

    if (container.querySelectorAll('input[data-type="datepicker"]')) {
      container.querySelectorAll('input[data-type="datepicker"]').forEach(input => {
        singleDatepicker(input) // function by app.blade.php
      })
    }
  },

  formData: form => {
    let data = {}
    $(form).serializeArray().forEach(item => {
      let inputs = form.querySelectorAll('[name="'+ item.name +'"]')

      if (inputs.length > 1 && inputs[0].type !== 'radio') {
        if (!data[item.name]) {
          data[item.name] = []
        }

        let field = inputs.querySelector('[value="' + item.value + '"]')

        data[item.name].push(Modal.castsFormField(field, item.value))
      } else {
        data[item.name] = Modal.castsFormField(inputs[0], item.value)
      }
    })

    return data
  },

  castsFormField: (input, value) => {
    if (input.dataset.cast) {
      switch (input.dataset.cast) {
        case 'integer':
        case 'number':
          value = value.replace(/[^0-9,.]/g, '')
          if (!value) return null
          return parseInt(value) ?? null
          break
        case 'float':
        case 'double':
          value = value.replace(/[^0-9,.]/g, '').replace(',', '.')
          if (!value) return null
          return parseFloat(value) ?? null
          break
        default:
          return value
          break
      }
    }

    return value
  },

  handleForms: container => {
    // Поставим фокус в конец строки, для input[autofocus]
    let focus = container[0].querySelector('[autofocus]')
    if (focus && focus.value.length) focus.selectionStart = focus.value.length;

    // Отправка формы
    let forms = container[0].querySelectorAll('form')
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault()

        axios.post(form.action, Modal.formData(form))
          .then(response => {
            if (response.data.ok) {
              if (response.data.data.url) {
                window.location.href = response.data.data.url
                return true
              }

              if (typeof formSuccessHandle !== 'undefined') {
                formSuccessHandle(response.data.data)
              } else {
                window.location.reload()
              }

            } else {
              return Modal.errorMessage(container, response.data)
            }
          })
          .catch(error => {
            console.log(error)
            return Modal.errorMessage(container, error.response.data)
          })

      })
    })
  }
};

/**
 *
 * @param table
 */
function mobileTable(table) {
  let header = table.querySelector('thead')
  let body = table.querySelector('tbody')
  let main_index = null
  let columns = []

  if (header.querySelectorAll('td').length) {
    header.querySelectorAll('td').forEach((column, index) => {
      columns.push(column.innerText.trim())
      if (!main_index && column.classList.contains('main')) {
        main_index = index
      }
    })
  }

  if (header.querySelectorAll('th').length) {
    header.querySelectorAll('th').forEach((column, index) => {
      columns.push(column.innerText.trim())
      if (!main_index && column.classList.contains('main')) {
        main_index = index
      }
    })
  }

  body.querySelectorAll('tr').forEach(row => {
    row.querySelectorAll('td').forEach((column, index) => {
      if (!column.dataset.label) {
        column.dataset.label = columns[index]
      }
      if (index === main_index) column.classList.add('main')
    })
  })
}

/**
 * Покупаю или перестал покупать эту монету
 * @param uuid
 * @returns {boolean}
 */
function buying(uuid) {
  axios.post('/users/coin/buying', {uuid})
    .then(response => {
      if (response.data.ok) {
        let btn = document.querySelector('a[data-buying][data-uuid="' + uuid + '"]');
        let icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">\n' +
          '  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />\n' +
          '</svg>'

        let message = document.createElement('span')
        message.style.color = 'rgb(0,200,0)'
        message.style.padding = '.3rem 0 .2rem'
        message.innerHTML = icon
        btn.after(message)

        btn.parentNode.removeChild(btn);
      } else {
        alert('Error')
      }
    })

  return false
}

/**
 * Соберет JSON из <script type="application/json" id="{container}">
 * @returns {*[]}
 * @param json
 * @param reverse
 */
function jsonToGraphData(json, reverse) {
  let data = [];

  Object.keys(json).forEach(date => {
    let value = parseFloat(json[date])
    if (reverse) value = json[date] * -1

    data.push({
      date: date,
      default_value: json[date],
      value: value
    })
  })

  return data;
}

/**
 * @param container
 */
function createGraph(container, reverse) {
  if (!container) return

  let data = JSON.parse(container.dataset.graphJson)
  if (!data) return

  // -------
  let root = am5.Root.new(container);

  root.setThemes([animated.new(root)]);

  root.dateFormatter.setAll({
    dateFormat: "yyyy-mm-dd",
    dateFields: ["valueX"]
  });

  let chart = root.container.children.push(am5xy.XYChart.new(root, {
    focusable: true,
    panX: true,
    panY: true,
    wheelX: 'panX',
    wheelY: 'zoomX',
  }));

  // let easing = am5.ease.linear;

  let xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
    maxDeviation: 0,
    tooltipDateFormat: "d MMM",
    groupData: false,
    baseInterval: {timeUnit: "day", count: 1},
    renderer: am5xy.AxisRendererX.new(root, {}),
    tooltip: am5.Tooltip.new(root, {})
  }));

  let data_clean = []
  Object.keys(data).forEach(index => {
    data_clean.push(parseFloat(data[index]))
  })
  console.log(data_clean)

  let configAxisY = {
    strictMinMax: true,
    renderer: am5xy.AxisRendererY.new(root, {})
  }

  if (reverse) {
    configAxisY.numberFormat = "#s" // "s" - убирает минусовые значения в плюс
  }

  let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, configAxisY));

  let series = chart.series.push(am5xy.SmoothedXLineSeries.new(root, {
    // noRisers: true,
    minBulletDistance: 20,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: 'value',
    valueXField: 'date',
    fill: am5.color(0xf52e2e),
    stroke: am5.color(0xf52e2e),
    tooltip: am5.Tooltip.new(root, {
      // pointerOrientation: "horizontal",
      labelText: (container.dataset.prefix ?? '') + "{default_value}",
    })
  }));

  series.fills.template.setAll({
    // fill: am5.color(0xf52e2e),
    // fillOpacity: 0.2,
    visible: false,
  });

  series.strokes.template.setAll({
    strokeWidth: 2
  });

  series.data.processor = am5.DataProcessor.new(root, {
    dateFormat: "yyyy-MM-dd",
    dateFields: ["date"]
  });

  series.data.setAll(jsonToGraphData(data, reverse));

  series.bullets.push(function () {
    let circle = am5.Circle.new(root, {
      radius: 4,
      fill: root.interfaceColors.get("background"),
      stroke: series.get("fill"),
      strokeWidth: 2
    })

    return am5.Bullet.new(root, {
      fill: root.interfaceColors.get("background"),
      sprite: circle
    })
  });

  let cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
    xAxis: xAxis,
    behavior: "zoomX"
  }));

  cursor.lineY.set("visible", false);

  chart.set("scrollbarX", am5.Scrollbar.new(root, {
    orientation: "horizontal"
  }));

  chart.appear(1000, 100);
}

// alteco рейтинг
function stepsProgress(elements) {
  elements.forEach(progress => {
    let value = progress.dataset.value
    if (!value) return

    if (value < 0 || isNaN(parseInt(value))) {
      value = 0
      progress.dataset.value = 0
    }

    if (value > 100) {
      value = 100
      progress.dataset.value = 100
    }

    let label = progress.querySelector('.label')
    let steps = progress.querySelectorAll('.step')
    let size = 100 / steps.length
    let active = Math.ceil(value / size)

    steps.forEach((step, index) => {
      if (index + 1 <= active) {
        step.classList.add('active')
      }
    })

    label.style.left = value + '%'

    let delay = 500
    let stepDelay = delay / steps.length
    let stepsCount = steps.length
    for(let i = 1; i <= stepsCount; i++) {
      setTimeout(() => {
        label.textContent = stepsCount === i
          ? value
          : parseInt(value / stepsCount * i)
      }, stepDelay * i)
    }
  })
}

window.numberAnimation = elements =>
{
  elements.forEach(item => {
    let steps = item.dataset.counterStep
    let defaultContent = item.textContent.trim()
    let value = item.dataset.number ?? defaultContent
    let decimals = item.dataset.decimals ?? 2

    if (!steps || isNaN(parseInt(steps)) || isNaN(value)) return null

    item.style.opacity = 0

    // item.textContent = 0
    let iterationValue = value / steps
    let delay = 500
    let delayIteration = delay / steps

    let numberFormat = new Intl.NumberFormat()

    let pseudo = document.createElement('span')
    pseudo.style.position = 'absolute'
    pseudo.style.marginLeft = '-'+ item.offsetWidth +'px'
    item.parentNode.appendChild(pseudo)

    for(let i = 1; i <= steps; i++) {
      setTimeout(() => {
        if (i === parseInt(steps) ) {
          pseudo.remove()
          item.style.opacity = 1
        } else {
          pseudo.textContent = numberFormat.format((iterationValue * i).toFixed(decimals))
        }
      }, delayIteration * i)
    }

    item.parentNode.style.removeProperty('width')
  })
}

let Tabs = {
  init: navigation => {
    let tabs = navigation.querySelectorAll('.tab')

    tabs.forEach(tab => {
      //
      tab.addEventListener('click', (e) => {
        e.preventDefault()
        navigation.querySelector('.tab.show').classList.remove('show')
        tab.classList.add('show')

        Tabs.update(navigation)
      })

      Tabs.update(navigation)
    })
  },

  update: navigation => {
    let tabs = navigation.querySelectorAll('.tab')

    tabs.forEach(tab => {
      let container = document.querySelector('#' + tab.dataset.for)

      if (tab.classList.contains('show')) {
        container.style.display = 'block'
      } else {
        container.style.display = 'none'
      }
    })
  }
}

let Dialog = {
  registerShortcut: () => {
    document.body.addEventListener('keypress', function(e) {
      if (e.key === "Escape") {
        return Dialog.close()
      }
    })

    document.querySelectorAll('[data-dialog-el="close"]').forEach(el => {
      el.addEventListener('click', (e) => {
        e.preventDefault()
        return Dialog.close()
      })
    })
  },

  show: id => {
    let dialog = document.querySelector('div[data-dialog-id="'+ id +'"]')
    if (!dialog) return

    dialog.dataset.show = true
    Dialog.bodyScroll(false)
  },

  close: () => {
    document.querySelectorAll('div[data-dialog-id][data-show]').forEach(dialog => {
      dialog.dataset.show = false
    })

    Dialog.bodyScroll(true)
  },

  bodyScroll: visible => {
    document.body.style.overflow = visible ? 'auto' : 'hidden'
  }
}

window.AppDialog = Dialog

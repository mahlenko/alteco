require('./bootstrap');
require('bootstrap/js/dist/dropdown')

import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import animated from "@amcharts/amcharts5/themes/Animated";

document.addEventListener("DOMContentLoaded", function(event) {
    // графики
    let graphs = document.querySelectorAll('[data-json]')
    graphs.forEach(graph => {
        createGraph(graph)
    })

    // "покупаю"
    let btn_buying = document.querySelectorAll('a[data-buying]');
    if (btn_buying) {
        btn_buying.forEach(btn => {
            btn.addEventListener('click', function() {
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

    $('select[multiple]').selectize({
        render: {
            option: function (item, escape) {
                // кнопка подписаться в выпадающем списке
                if (item.value === 'subscribe') {
                    return '<a href="'+ item.url +'" class="subscribe">'+ item.text +'</a>';
                }

                return '<div data-value="'+ item.value +'" class="option">'+ escape(item.text) +'</div>';
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

            return
        },
    });

});

/**
 *
 * @param table
 */
function mobileTable(table)
{
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
function buying(uuid)
{
    axios.post('/users/coin/buying', { uuid })
        .then(response => {
            if (response.data.ok) {
                let btn = document.querySelector('a[data-buying][data-uuid="'+ uuid +'"]');

                let message = document.createElement('span')
                message.classList.add('text-success')
                message.innerText = 'OK'
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
 */
function jsonToGraphData(json) {
    let data = [];
    Object.keys(json).forEach(date => {
        data.push({
            date: date,
            default_value: json[date],
            value: json[date] * -1
        })
    })

    return data;
}

/**
 * @param container
 */
function createGraph(container) {
    if (!container) return

    let data = JSON.parse(container.dataset.json)
    if (!data) return

    // -------
    let root = am5.Root.new(container);

    root.setThemes([ animated.new(root) ]);

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
        baseInterval: { timeUnit: "day", count: 1 },
        renderer: am5xy.AxisRendererX.new(root, {
        }),
        tooltip: am5.Tooltip.new(root, {})
    }));

    let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        maxPrecision: 0,
        numberFormat: "#s", // "s" - убирает минусовые значения в плюс
        renderer: am5xy.AxisRendererY.new(root, {
        })
    }));

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
            labelText: "{default_value}",
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

    series.data.setAll(jsonToGraphData(data));

    series.bullets.push(function() {
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

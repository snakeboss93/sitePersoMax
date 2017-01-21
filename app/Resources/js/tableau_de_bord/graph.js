/* eslint-disable */
/** Pie Chart **/
function type (d) {
  d.frequency = +d.frequency;
  return d;
}

$(document).ready(function () {
  if ($('#pieChart').length > 0) {
    var pie = new d3pie('pieChart', {
      'header': {
        'title': {
          'fontSize': 24
        },
        'subtitle': {
          'color': '#999999',
          'fontSize': 12
        },
        'titleSubtitlePadding': 9
      },
      'footer': {
        'color': '#999999',
        'fontSize': 10,
        'location': 'bottom-left'
      },
      'size': {
        'canvasHeight': 400,
        'canvasWidth': 400,
        'pieInnerRadius': '64%',
        'pieOuterRadius': '85%'
      },
      'data': {
        'content': [
          {
            'label': 'Manosque (MP)',
            'value': 100,
            'color': '#0066cc'
          },
          {
            'label': 'Monteux (MP)',
            'value': 150,
            'color': '#003366'
          },
          {
            'label': 'Brignoles (MDF)',
            'value': 200,
            'color': '#336600'
          },
          {
            'label': '(MDF)',
            'value': 50,
            'color': '#669966'
          },
          {
            'label': '(MP)',
            'value': 75,
            'color': '#990000'
          }
        ]
      },
      'labels': {
        'outer': {
          'pieDistance': 32
        },
        'inner': {
          'format': 'value'
        },
        'mainLabel': {
          'fontSize': 11
        },
        'percentage': {
          'color': '#ffffff',
          'decimalPlaces': 0
        },
        'value': {
          'color': '#adadad',
          'fontSize': 11
        },
        'lines': {
          'enabled': true
        },
        'truncation': {
          'enabled': true
        }
      },
      'effects': {
        'pullOutSegmentOnClick': {
          'effect': 'linear',
          'speed': 400,
          'size': 8
        }
      },
      'misc': {
        'gradient': {
          'enabled': true,
          'percentage': 100
        }
      }
    });

        /** Bar Chart **/

    var margin = {top: 20, right: 20, bottom: 30, left: 40},
      width = 400 - margin.left - margin.right,
      height = 300 - margin.top - margin.bottom;

    var x = d3.scale.ordinal()
            .rangeRoundBands([0, width], 0.1);

    var y = d3.scale.linear()
            .range([height, 0]);

    var xAxis = d3.svg.axis()
            .scale(x)
            .orient('bottom');

    var yAxis = d3.svg.axis()
            .scale(y)
            .orient('left');

    var svg = d3.select('#barChart').append('svg')
            .attr('width', width + margin.left + margin.right)
            .attr('height', height + margin.top + margin.bottom)
            .append('g')
            .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')');

    d3.tsv('/built/tableau_de_bord/data.tsv', type, function (error, data) {
      if (error) {
        throw error;
      }

      x.domain(data.map(function (d) {
        return d.letter;
      }));
      y.domain([0, d3.max(data, function (d) {
        return d.frequency;
      })]);

      svg.append('g')
                .attr('class', 'x axis')
                .attr('transform', 'translate(0,' + height + ')')
                .call(xAxis);

      svg.append('g')
                .attr('class', 'y axis')
                .call(yAxis)
                .append('text')
                .attr('transform', 'rotate(-90)')
                .attr('y', 6)
                .attr('dy', '.71em')
                .style('text-anchor', 'end')
                .text('contrat');

      svg.selectAll('.bar')
                .data(data)
                .enter().append('rect')
                .attr('class', 'bar')
                .attr('x', function (d) {
                  return x(d.letter);
                })
                .attr('width', x.rangeBand())
                .attr('y', function (d) {
                  return y(d.frequency);
                })
                .attr('height', function (d) {
                  return height - y(d.frequency);
                });
    });

        /** Graph Evolution **/

    var Emargin = {top: 50, right: 50, bottom: 100, left: 50},
      Ewidth = 700 - Emargin.left - Emargin.right,
      Eheight = 450 - Emargin.top - Emargin.bottom;

    var parseDate = d3.time.format('%b %Y').parse;

    var Ex = d3.time.scale().range([0, Ewidth]),
      Ey = d3.scale.linear().range([Eheight, 0]);

    var ExAxis = d3.svg.axis().scale(Ex).orient('bottom'),
      EyAxis = d3.svg.axis().scale(Ey).orient('left');

    var area = d3.svg.area()
            .interpolate('monotone')
            .x(function (d) {
              return Ex(d.date);
            })
            .y0(Eheight)
            .y1(function (d) {
              return Ey(d.price);
            });

    var Esvg = d3.select('#evolutionChart').append('svg')
            .attr('width', Ewidth + Emargin.left + Emargin.right)
            .attr('height', Eheight + Emargin.top + Emargin.bottom);

    Esvg.append('defs').append('clipPath')
            .attr('id', 'clip')
            .append('rect')
            .attr('width', Ewidth)
            .attr('height', Eheight);

    var focus = Esvg.append('g')
            .attr('class', 'focus')
            .attr('transform', 'translate(' + Emargin.left + ',' + Emargin.top + ')');

    d3.csv('/built/tableau_de_bord/dataEvol.csv', Etype, function (error, data) {
      if (error) {
        throw error;
      }

      Ex.domain(d3.extent(data.map(function (d) {
        return d.date;
      })));
      Ey.domain([0, d3.max(data.map(function (d) {
        return d.price;
      }))]);

      focus.append('path')
                .datum(data)
                .attr('class', 'area')
                .attr('d', area);

      focus.append('g')
                .attr('class', 'x axis')
                .attr('transform', 'translate(0,' + Eheight + ')')
                .call(ExAxis);

      focus.append('g')
                .attr('class', 'y axis')
                .call(EyAxis);
    });
  }
  function Etype (d) {
    d.date = parseDate(d.date);
    d.price = +d.price;
    return d;
  }
});

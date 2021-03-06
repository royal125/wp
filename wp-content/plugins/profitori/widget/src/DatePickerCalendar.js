/* eslint-disable no-useless-constructor */
/* eslint-disable eqeqeq */
/* eslint-disable no-mixed-operators */

/*
Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
*/

var _react = _interopRequireDefault(require("react"));

var _propTypes = _interopRequireDefault(require("prop-types"));

var _reactstrap = require("reactstrap");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

export class DatePickerCalendar extends _react.default.Component {
  constructor(props) {
    super(props);
  }

  handleClick(e) {
    var day = e.currentTarget.getAttribute('data-day');
    var newSelectedDate = this.setTimeToNoon(new Date(this.props.displayDate));
    newSelectedDate.setDate(day);
    this.props.onChange(newSelectedDate);
  }

  handleClickToday() {
    var newSelectedDate = this.setTimeToNoon(new Date());
    this.props.onChange(newSelectedDate);
  }

  setTimeToNoon(date) {
    date.setHours(12);
    date.setMinutes(0);
    date.setSeconds(0);
    date.setMilliseconds(0);
    return date;
  }

  getWeekNumber(date) {
    var target = new Date(date.valueOf());
    var dayNr = (date.getDay() + 6) % 7;
    target.setDate(target.getDate() - dayNr + 3);
    var firstThursday = target.valueOf();
    target.setMonth(0, 1);

    if (target.getDay() !== 4) {
      target.setMonth(0, 1 + (4 - target.getDay() + 7) % 7);
    }

    return 1 + Math.ceil((firstThursday - target) / 604800000);
  }

  render() {
    var currentDate = this.setTimeToNoon(new Date());
    var selectedDate = this.props.selectedDate ? this.setTimeToNoon(new Date(this.props.selectedDate)) : null;
    var minDate = this.props.minDate ? this.setTimeToNoon(new Date(this.props.minDate)) : null;
    var maxDate = this.props.maxDate ? this.setTimeToNoon(new Date(this.props.maxDate)) : null;
    var year = this.props.displayDate.getFullYear();
    var month = this.props.displayDate.getMonth();
    var firstDay = new Date(year, month, 1);
    var startingDay = this.props.weekStartsOn > 1 ? firstDay.getDay() - this.props.weekStartsOn + 7 : this.props.weekStartsOn === 1 ? firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1 : firstDay.getDay();
    var showWeeks = this.props.showWeeks;
    var monthLength = daysInMonth[month];

    if (month == 1) {
      if (year % 4 == 0 && year % 100 != 0 || year % 400 == 0) {
        monthLength = 29;
      }
    }

    var weeks = [];
    var day = 1;

    for (var i = 0; i < 9; i++) {
      var week = [];

      for (var j = 0; j <= 6; j++) {
        if (day <= monthLength && (i > 0 || j >= startingDay)) {
          var className = null;
          var date = new Date(year, month, day, 12, 0, 0, 0).toISOString();
          var beforeMinDate = minDate && Date.parse(date) < Date.parse(minDate);
          var afterMinDate = maxDate && Date.parse(date) > Date.parse(maxDate);

          var clickHandler = ev => this.handleClick(ev);

          var style = {
            cursor: 'pointer',
            padding: this.props.cellPadding,
            borderRadius: this.props.roundedCorners ? 5 : 0
          };

          if (beforeMinDate || afterMinDate) {
            className = 'text-muted';
            clickHandler = null;
            style.cursor = 'default';
          } else if (Date.parse(date) === Date.parse(selectedDate)) {
            className = 'bg-primary';
          } else if (Date.parse(date) === Date.parse(currentDate)) {
            className = 'text-primary';
          }

          week.push(_react.default.createElement("td", {
            key: j,
            "data-day": day,
            onClick: clickHandler,
            style: style,
            className: className
          }, day));
          day++;
        } else {
          week.push(_react.default.createElement("td", {
            key: j
          }));
        }
      }

      if (showWeeks) {
        var weekNum = this.getWeekNumber(new Date(year, month, day - 1, 12, 0, 0, 0));
        week.unshift(_react.default.createElement("td", {
          key: 7,
          style: {
            padding: this.props.cellPadding,
            fontSize: '0.8em',
            color: 'darkgrey'
          },
          className: "text-muted"
        }, weekNum));
      }

      weeks.push(_react.default.createElement("tr", {
        key: i
      }, week));

      if (day > monthLength) {
        break;
      }
    }

    var weekColumn = showWeeks ? _react.default.createElement("td", {
      className: "text-muted current-week",
      style: {
        padding: this.props.cellPadding
      }
    }) : null;
    return _react.default.createElement("table", {
      className: "rdp-calendar text-center"
    }, _react.default.createElement("thead", null, _react.default.createElement("tr", null, weekColumn, this.props.dayLabels.map((label, index) => {
      return _react.default.createElement("td", {
        key: index,
        className: "text-muted",
        style: {
          padding: this.props.cellPadding
        }
      }, _react.default.createElement("small", null, label));
    }))), _react.default.createElement("tbody", null, weeks), this.props.showTodayButton && _react.default.createElement("tfoot", null, _react.default.createElement("tr", null, _react.default.createElement("td", {
      colSpan: this.props.dayLabels.length,
      style: {
        paddingTop: '9px'
      }
    }, _react.default.createElement(_reactstrap.Button, {
      block: true,
      size: "sm",
      className: "u-today-button",
      onClick: () => this.handleClickToday()
    }, this.props.todayButtonLabel)))));
  }

}

DatePickerCalendar.propTypes = {
  selectedDate: _propTypes.default.object,
  displayDate: _propTypes.default.object.isRequired,
  minDate: _propTypes.default.string,
  maxDate: _propTypes.default.string,
  onChange: _propTypes.default.func.isRequired,
  dayLabels: _propTypes.default.array.isRequired,
  cellPadding: _propTypes.default.string.isRequired,
  weekStartsOn: _propTypes.default.number,
  showTodayButton: _propTypes.default.bool,
  todayButtonLabel: _propTypes.default.string,
  roundedCorners: _propTypes.default.bool,
  showWeeks: _propTypes.default.bool
};
//var _default = DatePickerCalendar;
//exports.default = _default;

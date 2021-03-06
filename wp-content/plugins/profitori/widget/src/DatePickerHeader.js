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

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

export class DatePickerHeader extends _react.default.Component {
  constructor(props) {
    super(props);
  }

  displayingMinMonth() {
    if (!this.props.minDate) return false;
    var displayDate = new Date(this.props.displayDate);
    var minDate = new Date(this.props.minDate);
    return minDate.getFullYear() == displayDate.getFullYear() && minDate.getMonth() == displayDate.getMonth();
  }

  displayingMaxMonth() {
    if (!this.props.maxDate) return false;
    var displayDate = new Date(this.props.displayDate);
    var maxDate = new Date(this.props.maxDate);
    return maxDate.getFullYear() == displayDate.getFullYear() && maxDate.getMonth() == displayDate.getMonth();
  }

  handleClickPrevious() {
    var newDisplayDate = new Date(this.props.displayDate);
    newDisplayDate.setDate(1);
    newDisplayDate.setMonth(newDisplayDate.getMonth() - 1);
    this.props.onChange(newDisplayDate);
  }

  handleClickNext() {
    var newDisplayDate = new Date(this.props.displayDate);
    newDisplayDate.setDate(1);
    newDisplayDate.setMonth(newDisplayDate.getMonth() + 1);
    this.props.onChange(newDisplayDate);
  }

  render() {
    return _react.default.createElement("div", {
      className: "rdp-header text-center"
    }, _react.default.createElement("div", {
      className: "text-muted float-left rdp-header-previous-wrapper",
      onClick: () => this.handleClickPrevious(),
      style: {
        cursor: 'pointer'
      }
    }, this.displayingMinMonth() ? null : this.props.previousButtonElement), _react.default.createElement("span", null, this.props.monthLabels[this.props.displayDate.getMonth()], " ", this.props.displayDate.getFullYear()), _react.default.createElement("div", {
      className: "text-muted float-right rdp-header-next-wrapper",
      onClick: () => this.handleClickNext(),
      style: {
        cursor: 'pointer'
      }
    }, this.displayingMaxMonth() ? null : this.props.nextButtonElement));
  }

}

DatePickerHeader.propTypes = {
  displayDate: _propTypes.default.object.isRequired,
  minDate: _propTypes.default.string,
  maxDate: _propTypes.default.string,
  onChange: _propTypes.default.func.isRequired,
  monthLabels: _propTypes.default.array.isRequired,
  previousButtonElement: _propTypes.default.oneOfType([_propTypes.default.string, _propTypes.default.object]).isRequired,
  nextButtonElement: _propTypes.default.oneOfType([_propTypes.default.string, _propTypes.default.object]).isRequired
};
//var _default = DatePickerHeader;
//exports.default = _default;

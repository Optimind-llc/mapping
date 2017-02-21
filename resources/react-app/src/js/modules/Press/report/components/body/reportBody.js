import React, { PropTypes, Component } from 'react';
// Styles
import styles from './reportBody.scss';
// Components

class reportBody extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      choku: {label: '白直', value: 'W'}
    };
  }

  render() {
    const { lines, vehicles, combinations, count } = this.props;
    const { choku } = this.state;

    return (
      <div className="bg-white report-body">
        {
          lines.map((l, li) =>
            <div key={li} className={`report-panel ${count[l] ? '' : 'disabled'}`}>
              <p className="report-line-name">{l}</p>
              <p className="report-vehicle-head">車種</p>
              <div className="report-vehicle-body">
                {
                  combinations.filter(c =>
                    c.l === l
                  ).map(c =>
                    c.v
                  ).filter((x, i, self) =>
                    self.indexOf(x) === i
                  ).map((v, vi) =>
                    <p key={vi}>{v}</p>
                  )
                }
              </div>
            </div>
          )
        }
      </div>
    );
  }
}

reportBody.propTypes = {
  lines: PropTypes.array.isRequired,
  vehicles: PropTypes.array.isRequired,
  combinations: PropTypes.array.isRequired,
  count: PropTypes.object.isRequired
};

export default reportBody;

import React, { PropTypes, Component } from 'react';
// Styles
import styles from './mappingBody.scss';
// Components
import Loading from '../../../../../components/loading/loading';

class MappingBody extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      active: 'f',
      fFilter: [],
      fFilterK: [],
      fFilterS: [],
      fFilterZ: [],
      fFilterUK: []
    };
  }

  renderContent() {
    const { data } = this.props;
    const { active, fFilter, fFilterK, fFilterS, fFilterZ, fFilterUK } = this.state;

    switch (active) {
      case 'f':
        return (
          <div className="failure">
            <div className="collection">
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilter;
                      if ( fFilter.length !== 0) newFilter = [];
                      else newFilter = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilter: newFilter });
                    }}
                  >
                    <span><p>{fFilter.length === 0 && '✔'}︎</p></span>
                    <span>不良区分</span>
                  </li>
                  {data.failureTypes.map(ft =>{
                    const index = fFilter.indexOf(ft.id);
                    return (
                      <li
                        key={ft.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) fFilter.push(ft.id);
                          else fFilter.splice(index, 1);
                          this.setState({ fFilter });
                        }}
                      >
                        <span><p>{index === -1 && '✔'}︎</p></span>
                        <span>{ft.name}</span>
                      </li>
                    );
                  })}
                </ul>
              </div>
              {
                data.parts.map((p, i, self) =>
                  <div>
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0) + ' (' + p.failures.filter(f =>
                                f.typeId == ft.id
                              ).length + ')'
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
            </div>
          </div>
        );
      case 'res':
        return (
          <div className="responsibility">
            <div className="collection">
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilterK;
                      if ( fFilterK.length !== 0) newFilterK = [];
                      else newFilterK = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilterK: newFilterK });
                    }}
                  >
                    <span><p>{fFilterK.length === 0 && '✔'}︎</p></span>
                    <span>型保責</span>
                  </li>
                  {data.failureTypes.map(ft =>{
                    const index = fFilterK.indexOf(ft.id);
                    return (
                      <li
                        key={ft.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) fFilterK.push(ft.id);
                          else fFilterK.splice(index, 1);
                          this.setState({ fFilterK });
                        }}
                      >
                        <span><p>{index === -1 && '✔'}︎</p></span>
                        <span>{ft.name}</span>
                      </li>
                    );
                  })}
                </ul>
              </div>
              {
                data.parts.map((p, i, self) =>
                  <div className="not-has-check-box">
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 0
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0)
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilterS;
                      if ( fFilterS.length !== 0) newFilterS = [];
                      else newFilterS = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilterS: newFilterS });
                    }}
                  >
                    <span><p>{fFilterS.length === 0 && '✔'}︎</p></span>
                    <span>設保責</span>
                  </li>
                  {data.failureTypes.map(ft =>{
                    const index = fFilterS.indexOf(ft.id);
                    return (
                      <li
                        key={ft.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) fFilterS.push(ft.id);
                          else fFilterS.splice(index, 1);
                          this.setState({ fFilterS });
                        }}
                      >
                        <span><p>{index === -1 && '✔'}︎</p></span>
                        <span>{ft.name}</span>
                      </li>
                    );
                  })}
                </ul>
              </div>
              {
                data.parts.map((p, i, self) =>
                  <div className="not-has-check-box">
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 1
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0)
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilterZ;
                      if ( fFilterZ.length !== 0) newFilterZ = [];
                      else newFilterZ = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilterZ: newFilterZ });
                    }}
                  >
                    <span><p>{fFilterZ.length === 0 && '✔'}︎</p></span>
                    <span>材料責</span>
                  </li>
                  {data.failureTypes.map(ft =>{
                    const index = fFilterZ.indexOf(ft.id);
                    return (
                      <li
                        key={ft.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) fFilterZ.push(ft.id);
                          else fFilterZ.splice(index, 1);
                          this.setState({ fFilterZ });
                        }}
                      >
                        <span><p>{index === -1 && '✔'}︎</p></span>
                        <span>{ft.name}</span>
                      </li>
                    );
                  })}
                </ul>
              </div>
              {
                data.parts.map((p, i, self) =>
                  <div className="not-has-check-box">
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 2
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0)
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
              <div className="has-check-box">
                <ul>
                  <li
                    onClick={() => {
                      let newFilterUK;
                      if ( fFilterUK.length !== 0) newFilterUK = [];
                      else newFilterUK = data.failureTypes.map(ft => ft.id);
                      this.setState({ fFilterUK: newFilterUK });
                    }}
                  >
                    <span><p>{fFilterUK.length === 0 && '✔'}︎</p></span>
                    <span>責任不明</span>
                  </li>
                  {data.failureTypes.map(ft =>{
                    const index = fFilterUK.indexOf(ft.id);
                    return (
                      <li
                        key={ft.id}
                        className={index === -1 ? 'active' : ''}
                        onClick={() => {
                          if ( index === -1) fFilterUK.push(ft.id);
                          else fFilterUK.splice(index, 1);
                          this.setState({ fFilterUK });
                        }}
                      >
                        <span><p>{index === -1 && '✔'}︎</p></span>
                        <span>{ft.name}</span>
                      </li>
                    );
                  })}
                </ul>
              </div>
              {
                data.parts.map((p, i, self) =>
                  <div className="not-has-check-box">
                    <ul className="parts">
                      <li>{self.length === 1 ? '' : i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 99
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.mQty ? f.mQty: f.fQty
                              ).reduce((prev, current, i, arr) => {
                                return prev+current;
                              }, 0)
                            }
                          </li>
                        )
                      }
                    </ul>
                  </div>
                )
              }
            </div>
          </div>
        )
      case 'ato':
        return (
          <div></div>
        );
    }
  }

  render() {
    const { data, isFetching, didInvalidate, narrowedBy } = this.props;
    const { active, fFilter, fFilterK, fFilterS, fFilterZ, fFilterUK } = this.state;

    return (
      <div className="mapping-body-wrap">
        <div className="bg-white mapping-body">
          {
            <div className="color-label">
              <div>
                <div className="circle-red"></div>
                <p>白直</p>
              </div>
              <div>
                <div className="circle-yellow"></div>
                <p>黄直</p>
              </div>
            </div>
          }
          <div className="figure-wrap">
            <div>
              {
                data.parts.map((pt, i, self) =>
                  <div
                    style={{
                      position: 'relative',
                      float: 'left',
                      width: 846/self.length,
                      height: 520,
                      backgroundImage: `url(${pt.figure})`,
                      backgroundSize: 'contain',
                      backgroundPosition: 'center center',
                      backgroundRepeat: 'no-repeat'
                    }}
                  >
                  </div>
                )
              }
            </div>
            <svg>
              {
                active !== 'ato' &&
                data.parts.map((part, i, self) =>
                  part.failures.filter(f =>
                    fFilter.indexOf(f.typeId) == -1
                  ).filter(f => {
                    if (f.responsibleFor === 0) {
                      return fFilterK.indexOf(f.typeId) == -1;
                    }
                    return true;
                  }).filter(f => {
                    if (f.responsibleFor === 1) {
                      return fFilterS.indexOf(f.typeId) == -1;
                    }
                    return true;
                  }).filter(f => {
                    if (f.responsibleFor === 2) {
                      return fFilterZ.indexOf(f.typeId) == -1;
                    }
                    return true;
                  }).filter(f => {
                    if (f.responsibleFor === 99) {
                      return fFilterUK.indexOf(f.typeId) == -1;
                    }
                    return true;
                  }).map(f => {
                    const fill = f.c === 'Y' ? '#C6B700' : 'red';

                    return (
                      <g>
                        <circle
                          cx={f.x + 846/(self.length)*i}
                          cy={f.y}
                          r={5}
                          fill={fill}
                        />
                        <text
                          x={f.x + 846/(self.length)*i}
                          y={f.y - 10}
                          dy="4"
                          fontSize="10"
                          fill="#000"
                          textAnchor="middle"
                          fontWeight="bold"
                        >
                          {/*f.mQty ? f.mQty : f.fQty*/}
                        </text>
                      </g>
                    );
                }))
              }
            </svg>
          </div>
          <div className="control-panel">
            <div className="control-tab">
              <button
                className={active == 'f' ? '' : 'disable'}
                onClick={() => this.setState({ active: 'f', fFilterK: [], fFilterS: [], fFilterZ: []})}
              >
                自工程内不良
              </button>
              <button
                className={active == 'res' ? '' : 'disable'}
                onClick={() => this.setState({ active: 'res', fFilter: []})}
              >
                自工程内責任別
              </button>
              <button
                className={active == 'ato' ? '' : 'disable'}
                onClick={() => this.setState({ active: 'ato'})}
              >
                後工程流出
              </button>
            </div>
            <div className="control-content">
              {this.renderContent()}
            </div>
          </div>
          {
            isFetching && <Loading/>
          }{
            !isFetching && data.failureTypes.length == 0 && narrowedBy !== 'realtime' &&
            <div className="cover">
              <p>検査結果が見つかりませんでした</p>
            </div>
          }{
            didInvalidate && narrowedBy !== 'realtime' &&
            <div className="cover">
              <p>検査結果が見つかりませんでした</p>
            </div>
          }
        </div>
      </div>
    );
  }
}

MappingBody.propTypes = {
  data: PropTypes.object.isRequired,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  narrowedBy: PropTypes.string.isRequired
};

export default MappingBody;

import React, { PropTypes, Component } from 'react';
// Styles
import styles from './mappingBody.scss';
// Components

class MappingBody extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      active: 'f',
      fFilter: [],
      fFilterK: [],
      fFilterS: [],
      fFilterZ: []
    };
  }

  renderContent() {
    const { data } = this.props;
    const { active, fFilter, fFilterK, fFilterS, fFilterZ } = this.state;

    switch (active) {
      case 'f':
        return (
          <div className="failure">
            <div className="collection">
              <div>
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
                data.parts.map((p, i) =>
                  <div>
                    <ul className="parts">
                      <li>{i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {

                              p.failures.filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.fQty
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
        );
      case 'res':
        return (
          <div className="responsibility">
            <div className="collection">
              <div>
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
                data.parts.map((p, i) =>
                  <div>
                    <ul className="parts">
                      <li>{i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 0
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.fQty
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
              <div>
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
                data.parts.map((p, i) =>
                  <div>
                    <ul className="parts">
                      <li>{i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 1
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.fQty
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
              <div>
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
                data.parts.map((p, i) =>
                  <div>
                    <ul className="parts">
                      <li>{i == 0 ? 'L' : 'R'}</li>
                      {
                        data.failureTypes.map(ft => 
                          <li>
                            {
                              p.failures.filter(f =>
                                f.responsibleFor == 2
                              ).filter(f =>
                                f.typeId == ft.id
                              ).map(f =>
                                f.fQty
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
    const { data } = this.props;
    const { active, fFilter, fFilterK, fFilterS, fFilterZ } = this.state;

    return (
      <div className="mapping-body-wrap">
        <div className="bg-white mapping-body">
          {/*<div className="color-label">
            <div>
              <div className="circle-red"></div>
              <p>白直</p>
            </div>
            <div>
              <div className="circle-yellow"></div>
              <p>黄直</p>
            </div>
            <div>
              <div className="circle-blue"></div>
              <p>黒直</p>
            </div>
          </div>*/}
          <div className="figure-wrap">
            <div className="figure">
              {
                data.parts.length == 1 ?
                <img width="870" height="450" src={data.parts[0].figure}/> :
                data.parts.map(pt =>
                  <img width="435" height="450" src={pt.figure}/>
                )
              }
            </div>
            <svg>
              {
                data.parts.map((part, i) =>
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
                  }).map(f => {
                    return (
                      <g>
                        <circle cx={f.x + 450*i} cy={f.y} r={5} fill="red" />
                      </g>
                    );
                  // switch (f.c) {
                  //   case '白直':
                  //     return (
                  //       <g>
                  //         <circle cx={f.x} cy={f.y} r={5} fill="red" />
                  //       </g>
                  //     );
                  //     break;
                  //   case '黄直':
                  //     return (
                  //       <g>
                  //         <circle cx={f.x} cy={f.y} r={5} fill="#C6B700" />
                  //       </g>
                  //     );
                  //     break;
                  //   case '黒直':
                  //     return (
                  //       <g>
                  //         <circle cx={f.x} cy={f.y} r={5} fill="blue" />
                  //       </g>
                  //     );
                  //     break;
                  // }
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
                工程内不良
              </button>
              <button
                className={active == 'res' ? '' : 'disable'}
                onClick={() => this.setState({ active: 'res', fFilter: []})}
              >
                工程内責任別
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
        </div>
      </div>
    );
  }
}

MappingBody.propTypes = {
  data: PropTypes.object.isRequired,
  vehicles: PropTypes.array.isRequired
};

export default MappingBody;

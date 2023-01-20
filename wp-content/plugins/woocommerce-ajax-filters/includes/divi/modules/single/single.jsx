// External Dependencies
import React, { Component } from 'react';

class BAPFSingleFilter extends Component {

  static slug = 'et_pb_br_filter_single';
  constructor(props) {
    super(props);
    this.state = {
      error: null,
      isLoaded: false,
      content: null
    };
  }
  render() {
    const { error, isLoaded, content } = this.state;

    if (error) {
      return (<div>{error.message}</div>);
    } else if (!isLoaded) {
      return (<div class="et-fb-loader-wrapper"><div class="et-fb-loader"></div></div>);
    } else {
      return ({content});
    }
  }
  componentDidMount() {
      var body = new FormData();
    body.append('action', 'brapf_get_single_filter');
    body.append('filter_id', this.props.filter_id);
    
    fetch(
      window.et_fb_options.ajaxurl, 
      {
        body: body,
        method: 'POST',        
      }
    )
      .then(
        (result) => {
          this.setState({
            isLoaded: true,
            content: result
          });
        },
        (error) => {
          this.setState({
            isLoaded: true,
            error
          });
        }
      )
  }
}

export default BAPFSingleFilter;

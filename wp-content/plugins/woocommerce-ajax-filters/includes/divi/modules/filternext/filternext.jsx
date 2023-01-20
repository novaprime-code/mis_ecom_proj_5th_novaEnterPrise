// External Dependencies
import React, { Component } from 'react';

class BAPFFilterNext extends Component {

  static slug = 'et_pb_braapf_filter_next';

  render() {
    return (
      <div style={{padding:"2em 0", background: "#6c2eb9", color: "#fff", fontSize: "12px", fontWeight: "600", verticalAlign: "middle", textAlign: "center", borderRadius: "1em"}}>
        <h3 style={{color: "#000", textShadow: "1px 0px white, -1px 0px white, 0px 1px white, 0px -1px white", fontWeight: "900"}}>BeRocket Filter Next Product</h3>
        Next products query will be filtered(query must use WooCommerce shortcode hooks)
      </div>
    );
  }
}

export default BAPFFilterNext;

/* global window, document */
if (! window._babelPolyfill) {
    require('@babel/polyfill');
}

import React from 'react';
import ReactDOM from 'react-dom';
import PersonsList from './containers/PersonsList';

document.addEventListener('DOMContentLoaded', function() {
    ReactDOM.render(<PersonsList wpObject={window.wpr_object} />, document.getElementById('render-person-block-container'));
});

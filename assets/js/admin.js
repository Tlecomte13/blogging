/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'mdb-ui-kit/css/mdb.min.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import '../css/admin/admin.css'

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// create global $ and jQuery variables
import $ from 'jquery'
global.$ = global.jQuery = $;

//navbar
import '../css/admin/style.css'
import './navbar/main'

// mdbootstrap 5 + fontawesome
import 'mdb-ui-kit'
import '@fortawesome/fontawesome-free/js/all.min'

// button-loader
import './Utility/button-form-loader.js'
import './Utility/progressBar'

//web socket
import './Websocket/websocket'
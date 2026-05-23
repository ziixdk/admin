import '../css/app.css'
import 'tom-select/dist/css/tom-select.css'
import 'flatpickr/dist/flatpickr.min.css'
import 'nprogress/nprogress.css'
import 'leaflet/dist/leaflet.css'

import Alpine from 'alpinejs'
import Collapse from '@alpinejs/collapse'
import Focus from '@alpinejs/focus'
import 'flowbite'
import TomSelect from 'tom-select'
import flatpickr from 'flatpickr'
import NProgress from 'nprogress'
import Sortable from 'sortablejs'
import axios from 'axios'
import L from 'leaflet'

Alpine.plugin(Collapse)
Alpine.plugin(Focus)

window.Alpine = Alpine
window.TomSelect = TomSelect
window.flatpickr = flatpickr
window.NProgress = NProgress
window.Sortable = Sortable
window.axios = axios
window.L = L

NProgress.configure({ showSpinner: false })

Alpine.start()

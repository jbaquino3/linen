import Vue from 'vue'

// Register components
import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'

export function register () {
    const requireComponent = require.context( '@/components', true, /[A-Z]\w+\.(vue|js)$/)
    requireComponent.keys().forEach(fileName => {
        const componentConfig = requireComponent(fileName)
        const componentName = upperFirst( camelCase( fileName.split('/').pop().replace(/\.\w+$/, '')))
        Vue.component( componentName, componentConfig.default )
    })
}
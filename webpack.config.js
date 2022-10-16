const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');

const path = require('path');

module.exports = {
    plugins: [
        new VuetifyLoaderPlugin()
    ],

    optimization: {
        splitChunks: {
            chunks: 'all'
        }
    },

    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        }
    },
};
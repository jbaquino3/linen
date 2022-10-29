<template>
    <div>
        <template>
            <v-app :style="vuetify.theme.dark? 'background: #212121' : 'background: #e8e8e8'">
                <v-main app>
                    <LandingPage></LandingPage>
                </v-main>
            </v-app>
        </template>
    </div>
</template>

<script>
    import { useVuetify } from '@/plugins/UseVuetify'
    import { onMounted } from 'vue'

    export default {
        setup() {
            const vuetify = useVuetify()

            onMounted(() => {
                vuetify.theme.dark = JSON.parse(localStorage.getItem('dark'))
            })

            return {
                vuetify
            }
        },

        components: {
            LandingPage: () => import('@/app/LandingPage')
        }
    }
</script>

<style>
    @media print {
        .v-main {
            padding: 0 !important;
        }
        .no-print {
            display: none !important;
        }
        .no-break {
            page-break-inside: avoid;
        }
    }

    .v-application {
        font-family: 'Segoe UI Variable', 'Segoe UI', sans-serif !important;
    }

    .required:after {
        content:" *";
        color: red;
    }

    .v-dialog {
        position: absolute;
        top: 0;
    }

    .v-btn {
        text-transform:none !important;
    }

    .v-stepper__header {
        box-shadow: none;
    }

    .v-card{
        border-radius: 8px !important;
    }

    .v-btn{
        border-radius: 4px !important;
    }

    .v-tab {
        text-transform: none !important;
    }

    .rounded{
        border-radius: 8px !important;
    }

    .custom-loader {
        animation: loader 1s infinite;
        display: flex;
    }

    @-moz-keyframes loader {
        from {
            transform: rotate(0);
        }
        to {
            transform: rotate(360deg);
        }
    }
    @-webkit-keyframes loader {
        from {
            transform: rotate(0);
        }
        to {
            transform: rotate(360deg);
        }
    }
    @-o-keyframes loader {
        from {
            transform: rotate(0);
        }
        to {
            transform: rotate(360deg);
        }
    }
    @keyframes loader {
        from {
            transform: rotate(0);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>
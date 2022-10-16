<template>
    <div v-if="total > 0">
        <v-progress-linear
            :value="disabled ? 0 : 100*available/total"
            :color="disabled ? 'red' : getColor(100*available/total)"
            height="25"
        >
            <template v-slot:default="{ }">
                <div class="caption" v-if="disabled">
                    {{disabled_label}}
                </div>
                <div v-else class="caption">
                    {{formatnumber(available)}}
                    <span class="ml-1" v-if="unit">{{unit.toLowerCase()}}/s</span>
                </div>
            </template>
        </v-progress-linear>
    </div>
</template>

<script>
    export default {
        setup() {
            function getColor(item) {
                if (item > 75) {
                    return 'green'
                } else if (item > 50) {
                    return 'yellow'
                } else if (item > 25) {
                    return 'orange'
                }
                
                return 'red'
            }

            function formatnumber(number) {
                number = String(number)
                let dec_len = 0
                var sp = (number + '').split('.');
                if (sp[1] !== undefined) {
                    dec_len = sp[1].length
                } else {
                    dec_len = 0
                }

                if(dec_len > 2) {
                    return parseFloat(number).toFixed(2)
                } else {
                    return parseFloat(number)
                }
            }

            return {
                getColor,
                formatnumber
            }
        },

        props: {
            disabled: {
                type: Boolean,
                default: false
            },
            disabled_label: {
                type: String,
                default: "DISABLED"
            },
            available: {
                type: Number,
                default: 0
            },
            total: {
                type: Number,
                default: 0
            },
            unit: {
                type: String,
                default: null
            },
        }
    }
</script>
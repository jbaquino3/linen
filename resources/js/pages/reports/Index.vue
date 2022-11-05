<template>
    <div>
        <!-- <actions></actions>

        <v-alert v-if="transaction_error" type="error" text class="mt-2">
            {{transaction_error}}
        </v-alert> -->

        <div>
            <div ref="printable">
                <report></report>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        setup() {
            
        },

        components: {
            Report: () => import('./Report')
        }
    }
</script>
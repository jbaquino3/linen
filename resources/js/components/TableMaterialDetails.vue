<template>
    <div class="d-flex flex-column">
        <div :class="($vuetify.theme.dark ? 'yellow--text' : ' font-weight-medium') + ' d-flex'">
            <div class="subtitle-1 font-italic">
                {{description}}
            </div>
        </div>
        <div :class="($vuetify.theme.dark ? 'grey--text text--lighten-5' : '') + ' d-flex mb-2'">
            <div class="font-italic font-weight-bold red--text">#{{stockNumber}}</div>
            <v-chip dark x-small label class="ml-2" :color="type == 'RAW' ? 'blue' : 'red'">{{type}}</v-chip>
            <v-chip dark x-small label class="ml-2" color="green">
                ₱{{unitCost}}/{{unit.toLowerCase()}}
            </v-chip>
            <v-chip dark x-small label class="ml-2" color="orange" v-if="storageName">
                <v-icon left x-small>{{mdiDresser}}</v-icon>
                {{storageName}}
            </v-chip>
            <v-chip dark x-small label class="ml-2" color="grey darken-3" v-if="quantity">
                Used: {{quantity}}{{unit.toLowerCase()}}/s
            </v-chip>
        </div>
    </div>
</template>

<script>
    import { mdiDresser } from '@mdi/js'

    export default {
        props: [
            'quantity', 'unit', 'description', 'stockNumber', 'type', 'unitCost', 'storageName'
        ],

        setup() {
            return {
                ...icons
            }
        },
    }

    const icons = {mdiDresser}
</script>
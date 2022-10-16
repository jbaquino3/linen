<template>
    <v-tooltip top>
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-bind="attrs" v-on="on" small elevation="0" color="blue" dark @click="$emit('click')">
                <v-icon>
                    {{mdiPencil}}
                </v-icon>
            </v-btn>
        </template>
        <span>Edit</span>
    </v-tooltip>
</template>

<script>
    import { mdiPencil } from '@mdi/js'

    export default {
        setup() {
            return {
                ...icons
            }
        },
    }

    const icons = {
        mdiPencil
    }
</script>
<template>
    <v-tooltip top>
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-bind="attrs" v-on="on" small elevation="0" color="orange" dark @click="$emit('click')">
                <v-icon>
                    {{mdiPlaylistEdit}}
                </v-icon>
            </v-btn>
        </template>
        <span>Edit</span>
    </v-tooltip>
</template>

<script>
    import { mdiPlaylistEdit } from '@mdi/js'

    export default {
        setup() {
            return {
                ...icons
            }
        },
    }

    const icons = {
        mdiPlaylistEdit
    }
</script>
<template>
    <v-card flat>
        <v-container fluid class="d-flex flex-column flex-grow-1 flex-shrink-0">
            <div class="d-flex flex-row">
                <template v-for="item in actions">
                    <v-btn :key="item.emit" :color="item.color" dark @click="$emit(item.emit)">
                        <v-icon left v-if="item.icon">{{item.icon}}</v-icon>
                        {{item.text}}
                    </v-btn>
                </template>

                <v-spacer></v-spacer>

                <v-btn color="error" @click="clear_filters">
                    <v-icon>
                        {{mdiFilterRemove}}
                    </v-icon>
                </v-btn>

                <v-btn color="success" class="ml-2" @click="$emit('reload')">
                    <v-icon>
                        {{mdiReload}}
                    </v-icon>
                </v-btn>
            </div>

            <div class="d-flex mt-3">
                <v-text-field
                    v-if="hasSearch"
                    label="Search"
                    v-model="search"
                    hide-details
                    class="mr-2 shrink"
                    outlined
                    :prepend-inner-icon="mdiMagnify"
                    dense
                    @input="v => { $emit('search', v) }"
                ></v-text-field>

                <v-spacer></v-spacer>

                <template v-for="item in filterable">
                    <v-select
                        :key="item.value"
                        :label="item.text"
                        :multiple="item.type=='distinct'"
                        :small-chips="item.type=='distinct'"
                        :prepend-inner-icon="mdiFilter"
                        hide-details
                        class="ml-2 shrink"
                        dense
                        deletable-chips
                        outlined
                        clearable
                        :items="item.items"
                        :value="value[item.value]"
                        @input="val => apply_filters(item.value, val)"
                    ></v-select>
                </template>
            </div>
        </v-container>
    </v-card>
</template>

<script>
    import { mdiReload, mdiFilterRemove, mdiFilter, mdiMagnify } from '@mdi/js'

    export default {
        name: "TableFilters",

        props: {
            filterable: {type: Array, default: ()=>[]},
            value: {type: Object, default: ()=>{}},
            hasSearch: {type: Boolean, default: true},
            actions: {type: Array, default: () => []}
        },

        data: () => ({
            search: "",
            dt: null,

            mdiReload, mdiFilterRemove, mdiFilter, mdiMagnify
        }),

        methods: {
            clear_filters() {
                let filters = this.value
                for(var key in filters) {
                    filters[key] = null
                }
                this.search = ""
                this.$emit('input', filters)
                this.$emit('search', this.search)
            },

            apply_filters(key, val) {
                let filters = this.value
                filters[key] = val
                this.$emit('input', filters)
            }
        },
    }
</script>
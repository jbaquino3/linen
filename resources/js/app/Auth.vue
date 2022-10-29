<template>
    <div>
        <v-navigation-drawer :dark="darkMode" class="no-print" v-model="drawer" :bottom="vuetify.breakpoint.name == 'sm' || vuetify.breakpoint.name == 'xs'" fixed app>
            <template v-slot:prepend>
                <v-list-item dense>
                    <v-list-item-avatar size="32">
                        <img src="/assets/logo.png">
                    </v-list-item-avatar>

                    <v-list-item-title class="subtitle-1">Linen Inventory System</v-list-item-title>
                </v-list-item>
            </template>

            <v-divider></v-divider>

            <v-list shaped dense>
                <v-list-item-group v-model="selected_menu">
                    <template v-for="(item, i) in menus">
                        <template v-if="item.access">
                            <v-list-item :key="i" :to="item.route" active-class="blue darken-4 white--text">
                                <v-list-item-icon>
                                    <v-img width="20" :src="`/assets/${item.icon}.png`"></v-img>
                                </v-list-item-icon>
                                <v-list-item-title v-text="item.text"></v-list-item-title>
                            </v-list-item>
                        </template>
                    </template>
                </v-list-item-group>
            </v-list>

            <template v-slot:append>
                <v-sheet color="#212121">
                    <v-list-item class="white--text">
                        <v-list-item-title>
                            Dark Mode
                            <v-chip x-small class="ml-2 white--text" color="red">BETA</v-chip>
                        </v-list-item-title>

                        <v-list-item-action>
                            <v-switch v-model="darkMode"></v-switch>
                        </v-list-item-action>
                    </v-list-item>
                </v-sheet>
            </template>
        </v-navigation-drawer>

        <v-app-bar :dark="darkMode" dense elevation="0" class="no-print">
            <v-app-bar-nav-icon @click="drawer=!drawer"></v-app-bar-nav-icon>

            <v-app-bar-title>
                <div class="headline font-weight-bold">
                    {{$route.name}}
                </div>
            </v-app-bar-title>

            <v-spacer></v-spacer>

            <div class="title mr-2 d-none d-sm-flex">{{authStore.user? (authStore.user.name) : ""}}</div>
        </v-app-bar>

        <v-container fluid>
            <router-view></router-view>
        </v-container>
    </div>
</template>

<script>
    import { useAuthStore } from '@/stores/auth'
    import { ref, computed } from 'vue'
    import { useVuetify } from '@/plugins/UseVuetify'

    export default {
        setup() {
            const authStore = useAuthStore()
            const vuetify = useVuetify()
            const drawer = ref(true)
            const selected_menu = ref(0)

            const darkMode = computed({
                get() {
                    return vuetify.theme.dark
                },
                set(v) {
                    localStorage.setItem('dark', JSON.stringify(v))
                    vuetify.theme.dark = JSON.parse(localStorage.getItem('dark'))
                },
            })

            return {
                authStore, drawer, selected_menu,
                darkMode, vuetify, menus
            }
        },
    }

    const menus = [
        {icon: "dashboard", text: "Dashboard", route: "/auth/dashboard", access: true},
        {icon: "request", text: "Requests", route: "/auth/requests", access: true},
        {icon: "transaction", text: "Issuance", route: "/auth/issuances", access: true},
        {icon: "location", text: "Ward & Offices", route: "/auth/locations", access: true},
        {icon: "material", text: "Materials", route: "/auth/materials", access: true},
        {icon: "product", text: "Products", route: "/auth/products", access: true},
        {icon: "storage", text: "Storage Management", route: "/auth/storages", access: true},
        {icon: "report", text: "Monthly Reports", route: "/auth/reports", access: true}
    ]
</script>
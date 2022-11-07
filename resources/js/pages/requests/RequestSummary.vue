<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="4">
                    <v-card class="red white--text" flat>
                        <v-card-title class="text-center">
                            <v-spacer></v-spacer>
                            PENDING
                            <v-spacer></v-spacer>
                        </v-card-title>
                        <v-card-text class="white--text text-center display-2">
                            {{stats.pending}}
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="4">
                    <v-card class="blue white--text" flat>
                        <v-card-title class="text-center">
                            <v-spacer></v-spacer>
                            PROCESSING
                            <v-spacer></v-spacer>
                        </v-card-title>
                        <v-card-text class="white--text text-center display-2">
                            {{stats.processing}}
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="4">
                    <v-card class="green white--text" flat>
                        <v-card-title class="text-center">
                            <v-spacer></v-spacer>
                            FOR PICKUP
                            <v-spacer></v-spacer>
                        </v-card-title>
                        <v-card-text class="white--text text-center display-2">
                            {{stats.ready}}
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <div class="d-flex" v-if="!noLink">
                <v-spacer></v-spacer>
                <v-btn text color="blue" @click="view_requests">View All Requests</v-btn>
            </div>
            <v-divider v-if="!noLink"></v-divider>
        </v-container>
    </div>
</template>

<script>
    import { useRequestStore } from '@/stores/request'
    import { reactive, onMounted } from 'vue'
    import { useRouter } from '@/plugins/UseRouter'

    export default {
        setup(props) {
            const requestStore = useRequestStore()
            const stats = reactive({
                pending: 0,
                processing: 0,
                ready: 0
            })
            const router = useRouter()

            onMounted(async () => {
                const res = await requestStore.fetchStats()
                if(res.status) {
                    stats.pending = res.data.pending
                    stats.processing = res.data.processing
                    stats.ready = res.data.ready
                }
            })

            function view_requests() {
                router.push("/auth/requests")
            }

            return { stats, view_requests, ...props }
        },

        props: {
            noLink: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import BaseTable from '@/Components/BaseTable'
import RadioButton from '@/Components/RadioButton'
import { Head, Link } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const selectedServer = ref(
    (new URL(window.location)).searchParams.get('server_ip') ??
    props.hosts[0].server_ip
)

watch(selectedServer, (value) => {
    const url = new URL(window.location)
    url.searchParams.set('server_ip', value)
    window.history.pushState(null, '', url.toString())
})

const props = defineProps({
    hosts: {
        type: Array,
        required: true,
    },
})

const tableHeaders = [
    { value: 'Title', key: 'title' },
    { value: 'Logs count', key: 'logs_count' },
]

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Hosts" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hosts
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex flex-row flex-wrap p-4">
                        <div
                            v-for="serverIp in new Set(hosts.map((h) => h.server_ip))"
                            :key="serverIp"
                            class="flex flex-row mx-2 hover:bg-slate-100 rounded-lg py-1 px-2 w-1/6"
                        >
                            <RadioButton
                                :id="serverIp"
                                v-model="selectedServer"
                                :value="serverIp"
                                class="mt-1 mr-3"
                            />
                            <label :for="serverIp">{{ serverIp }}</label>
                        </div>
                    </div>

                    <BaseTable
                        :head="tableHeaders"
                        :body="hosts.filter((h)=> h.server_ip === selectedServer)"
                        countable
                        actionable
                    >
                        <template #actions="{ item }">
                            <Link
                                :href="route('hosts.logs',item.title)"
                                class="mr-2 bg-amber-500 text-white px-2 py-1 rounded-lg text-sm"
                            >
                                Logs
                            </Link>
                        </template>
                    </BaseTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

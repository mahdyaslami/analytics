<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import BaseTable from '@/Components/BaseTable'
import Pagination from '@/Components/Pagination'
import { computed } from 'vue'

const logsData = computed(
    () => props.logs.data.map((l) => ({
        ...l,
        request: parseRequest(l),
    }))
)

const props = defineProps({
    logs: {
        type: Object,
        required: true,
    },
})

const tableHeaders = [
    { value: 'Port', key: 'port' },
    { value: 'Remote addr', key: 'remote_addr' },
    { value: 'Remote user', key: 'remote_user' },
    { value: 'Time local', key: 'time_local' },
    { value: 'Request', key: 'request' },
    { value: 'Status', key: 'status' },
    { value: 'Body bytes sent', key: 'body_bytes_sent' },
    { value: 'Referer', key: 'referer' },
    { value: 'User agent', key: 'user_agent' },
]

function formatDate(date) {
    return (new Date(date)).toLocaleDateString('en-IR', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
    })
}

function parseRequest(log) {
    const [method, route, version] = log.request.split(' ')
    return {
        method,
        route,
        version,
        url: `http://${log.host}${route}`,
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="route().params.host" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Logs of {{ route().params.host }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                    <BaseTable
                        :head="tableHeaders"
                        :body="logsData"
                        :count="logs.total"
                        :per-page="logs.per_page"
                        countable-descending
                    >
                        <template #time_local="{item}">
                            {{ formatDate(item.time_local) }}
                        </template>

                        <template #request="{ item }">
                            {{ item.request.method }}
                            <a
                                class="text-blue-600"
                                target="_blank"
                                :href="item.request.url"
                            >
                                {{ item.request.route }}
                            </a>
                            {{ item.request.version }}
                        </template>
                    </BaseTable>
                </div>

                <Pagination :meta="logs" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
table td{
    white-space: nowrap;
}
</style>

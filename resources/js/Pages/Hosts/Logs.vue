<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import BaseTable from '@/Components/BaseTable'
import Pagination from '@/Components/Pagination'

defineProps({
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
                        :body="logs.data"
                        countable
                    />
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

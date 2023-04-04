<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import BaseTable from '@/Components/BaseTable'
import Pagination from '@/Components/Pagination'
import TextInput from '@/Components/TextInput'
import InputError from '@/Components/InputError'
import PrimaryButton from '@/Components/PrimaryButton'
import { computed } from 'vue'

const logsData = computed(
    () => props.logs.data.map((l) => ({
        ...l,
        request: parseRequest(l),
    }))
)

const form = useForm({
    filters: route().params.filters || '',
})

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

function formatDate(timestamp) {
    const d = new Date(timestamp)

    const twoDigits = (number) => number.toString().padStart(2, 0)

    const date = `${d.getFullYear()}-${twoDigits(d.getMonth() + 1)}-${twoDigits(d.getDate())}`
    const time = `${twoDigits(d.getHours())}:${twoDigits(d.getMinutes())}:${twoDigits(d.getSeconds())}`

    return `${date} ${time}`
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

const search = () => {
    if (form.filters.trim() == '') {
        delete form.filters
    }

    form.get(route('hosts.logs', route().params.host))
}
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="route().params.host" />

        <template #header>
            <div class="flex flex-row">
                <h2 class="flex font-semibold text-xl text-gray-800 leading-tight w-2/3">
                    Logs of {{ route().params.host }}
                </h2>

                <h2 class="flex flex-col w-1/3">
                    <form class="flex flex-row justify-end" @submit.prevent="search">
                        <TextInput
                            id="filters"
                            v-model="form.filters"
                            placeholder="Filter by :column:search"
                            type="text"
                        />

                        <PrimaryButton
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Search
                        </PrimaryButton>
                    </form>

                    <InputError class="flex mt-2 justify-center" :message="form.errors.filters" />
                </h2>
            </div>
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

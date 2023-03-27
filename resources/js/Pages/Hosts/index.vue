<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import BaseTable from '@/Components/BaseTable'
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    hosts: {
        type: [Array, Object],
        required: true,
    },
})

const tableHeaders = [
    { value: 'Title', key: 'title' },
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
                    <BaseTable
                        :head="tableHeaders"
                        :body="hosts"
                        countable
                        actionable
                    >
                        <template #actions="{ item }">
                            <Link
                                :href="item.title"
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

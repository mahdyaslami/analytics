<template>
    <section class="flex flex-row my-4 max-w-7xl mx-auto sm:px-6 lg:px-8" dir="ltr">
        <div class="flex w-1/3">
            <!-- bg-gray-100 hover:text-gray-500 -->
            <InertiaLink
                v-if="prev?.url"
                :href="prev?.url"
                class="flex flex-row items-center py-1 px-3 mr-auto text-sm font-medium text-gray-500 rounded-lg
                    border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
                :class="{ 'bg-gray-100 cursor-not-allowed hover:text-gray-500':prev?.active }"
            >
                Previous
            </InertiaLink>
        </div>

        <nav class="flex items-center mx-auto w-1/3">
            <ul class="flex flex-row items-center">
                <li v-for="page in getLinks" :key="page">
                    <InertiaLink
                        :href="page.url"
                        class="px-4 py-2 mx-1 rounded-lg leading-tight text-gray-500 hover:bg-gray-100
                                hover:text-gray-700"
                        :class="{ 'bg-white shadow':page.active }"
                    >
                        {{ page.label }}
                    </InertiaLink>
                </li>
            </ul>
        </nav>

        <div class="flex w-1/3">
            <InertiaLink
                v-if="next?.url"
                :href="next?.url"
                class="flex flex-row items-center py-1 px-3 ml-auto text-sm font-medium text-gray-500 rounded-lg
                    border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
                :class="{ 'bg-gray-100 cursor-not-allowed hover:text-gray-500':next?.active }"
            >
                Next
            </InertiaLink>
        </div>
    </section>
</template>

<script setup>
import { Link as InertiaLink } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    meta: {
        type: Object,
        required: true,
    },
})

const prev = ref(null)
const next = ref(null)

const getLinks = computed(() => {
    const links = props.meta.links

    // remove 'previues' label link
    prev.value = links.shift()

    // remove 'next' label link
    next.value = links.pop()

    return links
})
</script>

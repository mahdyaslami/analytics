<script setup>
import { computed } from 'vue'
import RadioButton from '@/Components/RadioButton'

const emit = defineEmits(['update:modelValue'])

const props = defineProps({
    head: {
        type: [Array, Object],
        required: true,
    },
    body: {
        type: [Array, Object],
        required: true,
    },
    perPage: {
        type: Number,
        required: false,
        default: 15,
    },
    modelValue: {
        type: [Object, Number],
        default: null,
    },
    selectIndex: {
        type: Boolean,
        default: false,
    },
    countable: {
        type: Boolean,
        required: false,
    },
    countableDescending: {
        type: Boolean,
        required: false,
    },
    count: {
        type: Number,
        required: false,
        default: Number.MAX_SAFE_INTEGER,
    },
    selectable: {
        type: Boolean,
        required: false,
    },
    actionable: {
        type: Boolean,
        required: false,
    },
})

const selected = computed({
    get() {
        return props.modelValue
    },

    set(val) {
        emit('update:modelValue', val)
    },
})

function pluck(arr, key) {
    return arr.map((i) => i[key])
}

function getRowNumber(number) {
    const page = Number(route().params.page || 1)
    return number + ((page - 1) * props.perPage)
}
</script>

<template>
    <table class="min-w-full" dir="ltr">
        <thead class="border-gray-200 bg-gray-50">
            <tr class="text-left text-xs leading-4 text-gray-500">
                <th v-if="countable || countableDescending" class="px-6 py-3 font-medium uppercase tracking-wider">
                    #
                </th>

                <th v-if="selectable" class="px-6 py-3 w-1" />

                <th
                    v-for="(item, index) in pluck(head, 'value')"
                    :key="index"
                    class="px-6 py-3 font-medium uppercase tracking-wider"
                >
                    {{ item }}
                </th>

                <th v-if="actionable" class="px-6 py-3 font-medium uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>

        <tbody class="bg-white">
            <tr
                v-for="(item, index) in body"
                :key="index"
                class="border-gray-200 border-y"
            >
                <td v-if="countable" class="px-6 py-4">
                    {{ getRowNumber(Number(index) + 1) }}
                </td>

                <td v-if="countableDescending" class="px-6 py-4">
                    {{ count - getRowNumber(index) }}
                </td>

                <td v-if="selectable" class="px-6 py-3">
                    <RadioButton
                        v-model="selected"
                        :value="selectIndex ? index : item"
                    />
                </td>

                <td
                    v-for="(key, idx) in pluck(head, 'key')"
                    :key="idx"
                    class="px-6 py-4"
                >
                    <slot :name="key" :item="item">
                        {{ item[key] }}
                    </slot>
                </td>

                <td v-if="actionable" class="px-6 py-4">
                    <slot
                        name="actions"
                        :item="item"
                        :index="index"
                    />
                </td>
            </tr>
        </tbody>

        <tfoot class="border-gray-200 bg-gray-50">
            <tr class="text-left text-xs leading-4 text-gray-500">
                <th v-if="countable || countableDescending" class="px-6 py-3 font-medium uppercase tracking-wider">
                    #
                </th>

                <th v-if="selectable" class="px-6 py-3 w-1" />

                <th
                    v-for="(item, index) in pluck(head, 'value')"
                    :key="index"
                    class="px-6 py-3 font-medium uppercase tracking-wider"
                >
                    {{ item }}
                </th>

                <th v-if="actionable" class="px-6 py-3 font-medium uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </tfoot>
    </table>
</template>

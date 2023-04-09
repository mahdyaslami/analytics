<script setup>
import { computed } from 'vue'

const emit = defineEmits(['update:modelValue'])

const props = defineProps({
    modelValue: {
        type: [Object, Array, Boolean, Number, String],
        default: false,
    },
    value: {
        type: [Object, String, Number],
        default: null,
    },
})

const proxyChecked = computed({
    get() {
        return props.modelValue
    },

    set(val) {
        emit('update:modelValue', val)
    },
})
</script>

<template>
    <input
        v-model="proxyChecked"
        type="radio"
        :value="value"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300
            focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
    >
</template>

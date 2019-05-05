<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <input
                :id="field.name"
                type="text"
                @keyup="handleChange"
                @blur="handleChange"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value"
            />
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        handleChange(event) {
            Nova.$emit('field-update-' + event.type + '-' + this.slugField, {
                value: event.target.value
            })
        },
    },

    computed: {
        slugField() {
            return this.field.slug || 'Slug'
        }
    }
}
</script>

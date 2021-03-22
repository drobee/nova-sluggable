<template>
    <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
        <template slot="field">
            <input
                :id="field.name"
                :dusk="field.attribute"
                type="text"
                @keyup="handleChange"
                @blur="handleChange"
                class="w-full form-control form-input form-input-bordered"
                v-model="value"
                v-bind="extraAttributes"
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
        defaultAttributes() {
            return {
                type: this.field.type || 'text',
                min: this.field.min,
                max: this.field.max,
                step: this.field.step,
                pattern: this.field.pattern,
                placeholder: this.field.placeholder || this.field.name,
                class: this.errorClasses,
            }
        },
        extraAttributes() {
            const attrs = this.field.extraAttributes

            return {
                ...this.defaultAttributes,
                ...attrs,
            }
        },
        slugField() {
            return this.field.slug || 'Slug'
        }
    }
}
</script>

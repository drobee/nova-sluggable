<template>
    <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
        <template slot="field">
            <input
                :id="field.name"
                type="text"
                class="w-full form-control form-input form-input-bordered"
                :disabled="isReadonly"
                :dusk="field.attribute"
                v-model="value"
                v-bind="extraAttributes"
            />

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: () => ({
        validationErrors: new Errors(),
        updating: false,
        initialValue: ''
    }),

    /**
     * Mount the component.
     */
    mounted() {
        const eventType = this.field.options.event || 'keyup';
        Nova.$on('field-update-' + eventType + '-' + this.field.name, ({value}) => {
            this.generateSlug(value)
        })
    },

    methods: {
        /*
         * Generate the slug
         */
        generateSlug(value) {
            this.validationErrors = new Errors()
            const options = {
                model: this.field.model || null,
                options: this.field.options || null,
                attribute: this.field.attribute || null,
                updating: this.updating,
                initialValue: this.initialValue,
                value,
            }

            Nova.request().post('/nova-vendor/nova-sluggable/generate', options)
                    .then(response => {
                        this.value = response.data.slug
                    }).catch(error => {
                        if (error.response.status == 422) {
                            this.validationErrors = new Errors(error.response.data.errors)
                        }
                    })
        },
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
            this.initialValue = this.value
            if (this.value) {
                this.updating = true
            }
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
    },

    computed: {
        defaultAttributes() {
            return {
                placeholder: this.field.placeholder || this.field.name,
            }
        },

        extraAttributes() {
            const attrs = this.field.extraAttributes

            return {
                ...this.defaultAttributes,
                ...attrs,
            }
        },

        hasError() {
            return this.validationErrors.has(this.field.attribute)
        },

        firstError() {
            if (this.hasError) {
                return this.validationErrors.first(this.field.attribute)
            }
        }
    },
}
</script>

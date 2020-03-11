<template>
    <div>
        <md-steppers :md-active-step.sync="active" md-linear>
            <md-step id="first" md-label="First Step" md-description="Optional" :md-done.sync="first">
                <md-button class="md-raised md-primary" @click="setTestField('subject', subject)">Subject test</md-button>
                <div
                    v-for="theme in themes" :key="theme.id"
                    @click="setTestField('theme', theme.id)"
                >
                    {{ theme.name}}
                </div>
            </md-step>

            <md-step id="second" md-label="Second Step" :md-done.sync="second">
                <md-button class="md-raised md-primary" @click="setTestType('fixed')">Subject test</md-button>
                <md-button class="md-raised md-primary" @click="setTestType('first_wrong', subject)">Subject test</md-button>
            </md-step>
        </md-steppers>
        <div>

        </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import axios from 'axios'

export default {
    metaInfo: {
        title: 'Home',
    },
    layout: (h, page) => h(Layout, [page]),
    components: {
        Icon,
    },
    props: {
        themes: Array,
        subject: String,
    },
    data() {
        return {
            active: 'first',
            first: false,
            second: false,

            testData : {
                field: null,
                id: null,
                type: null,
            },
            question: null,
            testSession: [],
        }
    },
    methods: {
        setTestField (field, id) {
            this.first = true;
            this.active = 'second';

            this.testData.field = field;
            this.testData.id = id;

        },
        setTestType (type) {
            this.testData.type = type;
            this.startTest();

        },
        startTest() {
            axios.defaults.headers.common = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            };
            axios.post(this.route('api.v1.test'), this.testData)
                .then(response => {
                    console.log(response.data);
                })
                .catch(e => {
                    this.errors.push(e)
                })
        },

    },
}
</script>

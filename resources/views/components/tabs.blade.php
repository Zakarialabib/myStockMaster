@props(['active'])

<div x-data="tabList()">
    <div class="mb-3" role="tablist">
        <template x-for="(state, index) in tabStates" :key="index">
            <button x-text="tabLabel(index)" @click.self="setActiveTab(index)"
                class="px-4 py-1 mx-2 text-sm hover:bg-blue-500 hover:text-white"
                :class="state === true ? 'bg-blue-500 text-white' : 'text-zinc-800'" :id="`tab-${index}`"
                role="tab" :aria-selected="(state === true).toString()"
                :aria-controls="`tab-panel-${index}`"></button>
        </template>
    </div>
    {{ $slot }}
</div>

<script>
    function tabList() {
        return {
            tabHeadings: [],
            tabStates: [],
            addTab(label) {
                id = this.tabHeadings.indexOf(label);
                if (id < 0) {
                    this.tabHeadings.push(label);
                    this.tabStates.push(false);
                }
                id = this.tabHeadings.indexOf(label);
                if (label === '{{ $active }}') {
                    this.setActiveTab(id);
                }
                return id;
            },
            tabLabel(id) {
                if (typeof this.tabHeadings[id] === 'undefined') {
                    return '';
                }
                return this.tabHeadings[id];
            },
            tabState(id) {
                if (typeof this.tabStates[id] === 'undefined') {
                    return false;
                }
                return this.tabStates[id];
            },
            setActiveTab(id) {
                this.tabStates.forEach(function(value, index) {
                    this[index] = false;
                }, this.tabStates);
                this.tabStates[id] = true;
            }
        }
    }
</script>

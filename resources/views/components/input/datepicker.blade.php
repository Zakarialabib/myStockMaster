<div x-data="app()" x-init="[initDate(), getNoOfDays()]" x-cloak>
    <div class="container mx-auto">
        <div class="mb-5">
            {{-- <label for="datepicker" class="font-bold mb-1 text-zinc-700 block">Select Date</label> --}}
            <label for="datepicker" class="label">
                <span class="label-text">Tarikh Mula</span>
            </label>

            <div class="relative">
                <input type="hidden" name="date" x-ref="date" :value="datepickerValue" />
                <input type="text" x-on:click="initDate(datepickerValue), showDatepicker = !showDatepicker" x-model="datepickerValue"
                    x-on:keydown.escape="showDatepicker = false"
                    {{-- class="w-full pl-4 pr-10 py-3 leading-none rounded-lg shadow-sm focus:outline-none focus:shadow-outline text-zinc-600 font-medium" --}}
                    class="input input-bordered"
                    placeholder="Select date" />

                <div class="absolute top-0 right-0 px-3 h-full flex items-center">
                    <svg class="w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>

                <div class="absolute top-0 left-0 p-4 mt-12 bg-white rounded-lg shadow" style="width: 17rem"
                    x-show.transition="showDatepicker" @click.away="showDatepicker = false">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-zinc-800"></span>
                            <span x-text="year" class="ml-1 text-lg font-normal text-zinc-600"></span>
                        </div>
                        <div>
                            <button type="button"
                                class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-zinc-100"
                                @click="if (month == 0) {
                                                year--;
                                                month = 12;
                                            } month--; getNoOfDays()">
                                <svg class="inline-flex w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button type="button"
                                class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-zinc-100"
                                @click="if (month == 11) {
                                                month = 0; 
                                                year++;
                                            } else {
                                                month++; 
                                            } getNoOfDays()">
                                <svg class="inline-flex w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap mb-3 -mx-1">
                        <template x-for="(day, index) in DAYS" :key="index">
                            <div style="width: 14.26%" class="px-0.5">
                                <div x-text="day" class="text-xs font-medium text-center text-zinc-800"></div>
                            </div>
                        </template>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-3">
                        <template x-for="blankday in blankdays">
                            <div style="width: 14.28%" class="p-1 text-sm text-center border border-transparent"></div>
                        </template>
                        <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                            <div style="width: 14.28%" class="px-1 mb-1">
                                <div @click="getDateValue(date)" x-text="date"
                                    class="text-sm leading-none text-center transition duration-100 ease-in-out rounded-full cursor-pointer"
                                    :class="{
                        'bg-primary': isToday(date) == true, 
                        'text-zinc-600 hover:bg-primary': isToday(date) == false && isSelectedDate(date) == false,
                        'bg-primary-focus text-white hover:bg-opacity-75': isSelectedDate(date) == true 
                    }"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
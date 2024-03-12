<div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8">

    <div class="flex items-center justify-center">
        <div class="mx-auto w-full bg-white">
            <form class="py-4 px-9" wire:submit="save">
                <div class="mb-5">
                    <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">
                        Send files to this email:
                    </label>
                    <input type="email"
                           wire:model="email"
                           name="email" id="email" placeholder="example@domain.com"
                           class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"/>
                    <div>@error('email') {{ $message }} @enderror</div>
                </div>

                <div class="mb-6 pt-4" x-data="{ files: null }">
                    <label class="mb-5 block text-xl font-semibold text-[#07074D]">
                        Upload File
                    </label>

                    <div class="mb-8">
                        <input type="file"
                               x-on:change="files = Object.values($event.target.files)"
                               name="file" id="file" class="sr-only" wire:model="document" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword"/>
                        <label for="file"
                               class="relative flex min-h-[200px] items-center justify-center rounded-md border border-dashed border-[#e0e0e0] p-12 text-center">
                            <div>
                                <span class="mb-2 block text-xl font-semibold text-[#07074D]">Drop files here</span>
                                <span class="mb-2 block text-base font-medium text-[#6B7280]">Or</span>
                                <span class="inline-flex rounded border border-[#e0e0e0] py-2 px-7 text-base font-medium text-[#07074D] cursor-pointer">Browse</span>
                            </div>
                        </label>
                    </div>

                    <div class="mb-5 rounded-md bg-[#F5F7FB] py-4 px-8">
                        <div>@error('document') {{ $message }} @enderror</div>
                        <div class="flex items-center justify-between max-w-sm">
                            <div x-text="files ? files.map(file => file.name).join(', ') : 'Choose single file...'"></div>
                        </div>
                    </div>

                </div>

                <div>
                    <input class="hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none cursor-pointer"
                           type="submit" value="Send File"/>
                </div>
            </form>
        </div>
    </div>

</div>



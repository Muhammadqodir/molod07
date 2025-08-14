<!-- resources/views/components/profile-photo-upload.blade.php -->
@props(['name' => null])

@php
    $photoUrl = Auth::user()->getUserPic()
        ? asset('uploads/' . Auth::user()->getUserPic())
        : null;
@endphp
<div class="mt-5">
    <label class="text-md mt-5 text-gray-800 font-medium">Фото профиля</label>
    <div x-data="{
        fileName: null,
        filePreview: '{{ $photoUrl ?? '' }}',
        removeChecked: false,
        removePhoto() {
            this.fileName = null;
            this.filePreview = null;
            $refs.fileInput.value = null;
            this.removeChecked = true;
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file && file.size <= 2 * 1024 * 1024 && ['image/jpeg', 'image/png'].includes(file.type)) {
                this.fileName = file.name;
                const reader = new FileReader();
                reader.onload = e => this.filePreview = e.target.result;
                reader.readAsDataURL(file);
                this.removeChecked = false;
            } else {
                $dispatch('notify', 'Неверный формат или размер изображения.');
                this.removePhoto();
            }
        }
    }" class="flex mt-3 items-start space-x-4">

        <!-- Image block -->
        <div
            class="w-20 h-20 rounded-xl bg-[#e5efff] flex items-center justify-center text-2xl text-gray-700 font-medium overflow-hidden">
            <template x-if="filePreview">
                <img :src="filePreview" alt="Preview" class="object-cover w-full h-full rounded-xl" />
            </template>
            <template x-if="!filePreview">
                <span>{{ strtoupper(mb_substr(Auth::user()->getFullName() ?? ' ', 0, 1)) }}</span>
            </template>
        </div>

        <!-- Text and controls -->
        <div class="flex flex-col space-y-2">
            <p class="text-sm text-gray-500">Допустимые форматы фотографий: jpg, png. <br>Размер не более 2 Mb.</p>

            <div class="flex space-x-4 text-sm mt-2">
                <label for="photo-upload" class="flex items-center space-x-1 text-primary cursor-pointer">
                    <x-lucide-upload class="w-4 h-4" />
                    <span>Загрузить</span>
                </label>
                <button type="button" @click="removePhoto"
                    class="flex items-center space-x-1 text-gray-600 hover:text-gray-800">
                    <x-lucide-trash class="w-4 h-4" />
                    <span>Удалить</span>
                </button>
            </div>

            <input x-ref="fileInput" @change="handleFileChange" id="photo-upload" type="file" accept="image/jpeg,image/png"
                class="hidden" name="{{ $name }}" />
            <input type="checkbox" :checked="removeChecked" x-model="removeChecked" class="hidden" name="remove_{{ $name }}" />
        </div>
    </div>
</div>

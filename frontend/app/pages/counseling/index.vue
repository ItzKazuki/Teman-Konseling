<template>
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Konseling</h1>
        <p class="text-sm text-gray-500">Pantau dan tindak lanjuti permintaan konseling siswa</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="flex bg-white border border-gray-200 p-1 rounded-xl shadow-sm">
          <button v-for="tab in ['all', 'pending', 'scheduled', 'finished']" :key="tab" @click="activeTab = tab" :class="['px-4 py-1.5 rounded-lg text-xs font-bold transition-all capitalize',
            activeTab === tab ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-gray-50']">
            {{ tab }}
          </button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-red-100 text-red-600 rounded-lg flex items-center">
          <Icon name="tabler:alert-octagon" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-xs text-gray-500 font-medium">Urgency Tinggi</p>
          <p class="text-xl font-bold text-gray-800">{{ stats.highUrgency }}</p>
        </div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-primary-100 text-primary-600 rounded-lg flex items-center">
          <Icon name="tabler:calendar-event" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-xs text-gray-500 font-medium">Terjadwal Minggu Ini</p>
          <p class="text-xl font-bold text-gray-800">{{ stats.scheduled }}</p>
        </div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-amber-100 text-amber-600 rounded-lg flex items-center">
          <Icon name="tabler:clock-pause" class="w-6 h-6" />
        </div>
        <div>
          <p class="text-xs text-gray-500 font-medium">Menunggu Konfirmasi</p>
          <p class="text-xl font-bold text-gray-800">{{ stats.pending }}</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
      <div v-for="item in filteredRequests" :key="item.id"
        class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex flex-col lg:flex-row gap-6">

          <div class="flex-1 space-y-3">
            <div class="flex items-center gap-2">
              <span
                :class="['px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider', getUrgencyClass(item.urgency)]">
                {{ item.urgency }} Priority
              </span>
              <span
                :class="['px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider', getStatusClass(item.status)]">
                {{ item.status }}
              </span>
            </div>

            <div>
              <h3 class="text-lg font-bold text-gray-900">{{ item.title }}</h3>
              <p class="text-sm text-gray-600 line-clamp-2 mt-1 italic">"{{ item.description }}"</p>
            </div>

            <div class="flex items-center gap-4 text-xs text-gray-500 mt-4">
              <span class="flex items-center gap-1">
                <Icon name="tabler:user" /> ID Siswa: {{ item.student_id.slice(0, 8) }}...
              </span>
              <span class="flex items-center gap-1">
                <Icon name="tabler:calendar-plus" /> Dibuat: {{ formatDate(item.created_at) }}
              </span>
            </div>
          </div>

          <div class="lg:w-80 bg-gray-50 rounded-xl p-4 border border-gray-100">
            <div v-if="item.schedule" class="space-y-3">
              <div class="flex items-center gap-3">
                <img :src="item.schedule.counselor.avatar_url"
                  class="w-8 h-8 rounded-full border border-white shadow-sm" />
                <div>
                  <p class="text-xs font-bold text-gray-900">{{ item.schedule.counselor.name }}</p>
                  <p class="text-[10px] text-gray-500">{{ item.schedule.counselor.jabatan }}</p>
                </div>
              </div>

              <div class="pt-3 border-t border-gray-200 space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <span class="text-gray-500">Metode:</span>
                  <span class="font-bold flex items-center gap-1">
                    <Icon :name="item.schedule.method === 'chat' ? 'tabler:messages' : 'tabler:users-group'"
                      class="text-primary-600" />
                    {{ item.schedule.method === 'chat' ? 'Online Chat' : 'Tatap Muka' }}
                  </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-gray-500">Waktu:</span>
                  <span class="font-bold text-gray-900">{{ formatDate(item.schedule.schedule_date) }} | {{
                    item.schedule.time_slot }}</span>
                </div>
              </div>
            </div>

            <div v-else class="h-full flex flex-col items-center justify-center text-center py-4">
              <Icon name="tabler:calendar-off" class="text-gray-300 w-8 h-8 mb-2" />
              <p class="text-xs text-gray-500">Jadwal Belum Dibuat</p>
              <button class="mt-2 text-xs font-bold text-primary-600 hover:underline">Atur Jadwal Sekarang</button>
            </div>
          </div>

          <div class="flex lg:flex-col gap-2 justify-center lg:w-40">
            <NuxtLink :to="`/counseling/${item.id}`"
              class="flex-1 lg:flex-none py-2 px-4 bg-primary-600 text-white rounded-lg text-xs font-bold hover:bg-primary-700 transition">
              Tindak Lanjut
            </NuxtLink>
            <NuxtLink :to="`/counseling/detail/${item.id}`"
              class="flex-1 lg:flex-none py-2 px-4 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-50">
              Lihat Detail
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Data dari JSON Anda (Biasanya dipanggil lewat useApi)
const rawData = [
  {
    "id": "019b2033-8a96-7106-ba3a-308f502173dc",
    "student_id": "019af42d-9860-712a-8f85-d0e91a24d123",
    "title": "Lorem 1234",
    "description": "My Lorem ipsum dolor sit amet asfas ffakjf afkjhasjfkha faksjfhakjfafafhi23r82h feiufhuiwe 32ri23ubire",
    "urgency": "low",
    "status": "scheduled",
    "created_at": "2025-12-15T04:10:07.000000Z",
    "updated_at": "2025-12-16T08:51:58.000000Z",
    "schedule": {
      "id": "019b265b-f1d6-7017-bb42-afe0de1bd087",
      "request_id": "019b2033-8a96-7106-ba3a-308f502173dc",
      "counselor_id": "019af420-0535-7005-8188-872b3274c131",
      "method": "chat",
      "schedule_date": "2025-12-16T00:00:00.000000Z",
      "time_slot": "10:00",
      "status": "pending",
      "confirmed_at": null,
      "created_at": "2025-12-16T08:51:58.000000Z",
      "updated_at": "2025-12-16T08:51:58.000000Z",
      "counselor": {
        "id": "019af420-0535-7005-8188-872b3274c131",
        "nip": "198001012005011001",
        "name": "Ibu Lia Permata",
        "email": "lia.bk@sekolah.sch.id",
        "role": "bk",
        "jabatan": "Koordinator BK",
        "email_verified_at": "2025-12-06T14:45:30.000000Z",
        "created_at": "2025-12-06T14:45:30.000000Z",
        "updated_at": "2025-12-13T04:34:16.000000Z",
        "avatar_file_id": null,
        "phone_number": "",
        "is_available": true,
        "avatar_url": "http://api.teman-konseling.test/static/profile.png"
      }
    }
  },
  {
    "id": "019b2666-f5f0-7087-bf16-0d591fa4ea25",
    "student_id": "019af42d-9860-712a-8f85-d0e91a24d123",
    "title": "Nilai Saya turun",
    "description": "Nilai saya akhir akhir ini turun bu kenapa ya padahal saya sudah belajar dengan giat",
    "urgency": "low",
    "status": "scheduled",
    "created_at": "2025-12-16T09:04:00.000000Z",
    "updated_at": "2025-12-16T09:39:00.000000Z",
    "schedule": {
      "id": "019b2686-ff95-7150-8d34-4f5fd0bb6294",
      "request_id": "019b2666-f5f0-7087-bf16-0d591fa4ea25",
      "counselor_id": "019af420-06ce-70e2-b804-fd6e2beeb198",
      "method": "face-to-face",
      "schedule_date": "2025-12-16T00:00:00.000000Z",
      "time_slot": "10:00",
      "status": "pending",
      "confirmed_at": null,
      "created_at": "2025-12-16T09:39:00.000000Z",
      "updated_at": "2025-12-16T09:39:00.000000Z",
      "counselor": {
        "id": "019af420-06ce-70e2-b804-fd6e2beeb198",
        "nip": "198505152010022002",
        "name": "Pak Bima Sakti",
        "email": "bima.bk@sekolah.sch.id",
        "role": "bk",
        "jabatan": "Staf BK",
        "email_verified_at": "2025-12-06T14:45:30.000000Z",
        "created_at": "2025-12-06T14:45:30.000000Z",
        "updated_at": "2025-12-06T14:45:30.000000Z",
        "avatar_file_id": null,
        "phone_number": "",
        "is_available": true,
        "avatar_url": "http://api.teman-konseling.test/static/profile.png"
      }
    }
  },
  {
    "id": "019b2668-bb18-73a9-8d79-a6868acf68cf",
    "student_id": "019af42d-9860-712a-8f85-d0e91a24d123",
    "title": "fafas sdvasv",
    "description": "avsvas sdvdsvs wvrwevre r vbrvberv ef rv3revewvew er brber",
    "urgency": "medium",
    "status": "pending",
    "created_at": "2025-12-16T09:05:56.000000Z",
    "updated_at": "2025-12-16T09:05:56.000000Z",
    "schedule": null
  },
  {
    "id": "019b2669-85f6-735f-9c84-c0ed4ae754da",
    "student_id": "019af42d-9860-712a-8f85-d0e91a24d123",
    "title": "fasfa efojnwieu eknjfkjenf wkfnkjwef",
    "description": "ewfwejnfkjwe fewnfijwne fweijnfjwenjf efijejnfjkwef ejf kwenfjnwe wkefnwejnfjweifuifu23n ifewnjfnwef",
    "urgency": "medium",
    "status": "pending",
    "created_at": "2025-12-16T09:06:48.000000Z",
    "updated_at": "2025-12-16T09:06:48.000000Z",
    "schedule": {
      "id": "019b2669-ad5f-71d5-85b6-b47d71d82270",
      "request_id": "019b2669-85f6-735f-9c84-c0ed4ae754da",
      "counselor_id": "019af420-0535-7005-8188-872b3274c131",
      "method": "face-to-face",
      "schedule_date": "2025-12-16T00:00:00.000000Z",
      "time_slot": "11:00",
      "status": "pending",
      "confirmed_at": null,
      "created_at": "2025-12-16T09:06:58.000000Z",
      "updated_at": "2025-12-16T09:06:58.000000Z",
      "counselor": {
        "id": "019af420-0535-7005-8188-872b3274c131",
        "nip": "198001012005011001",
        "name": "Ibu Lia Permata",
        "email": "lia.bk@sekolah.sch.id",
        "role": "bk",
        "jabatan": "Koordinator BK",
        "email_verified_at": "2025-12-06T14:45:30.000000Z",
        "created_at": "2025-12-06T14:45:30.000000Z",
        "updated_at": "2025-12-13T04:34:16.000000Z",
        "avatar_file_id": null,
        "phone_number": "",
        "is_available": true,
        "avatar_url": "http://api.teman-konseling.test/static/profile.png"
      }
    }
  },
  {
    "id": "019b268b-8160-722e-b3fb-750f7deb57fd",
    "student_id": "019af42d-9860-712a-8f85-d0e91a24d123",
    "title": "Saya sedih",
    "description": "Ga tau kenapa kok akhir akhir ini saya sedih sendiri",
    "urgency": "medium",
    "status": "scheduled",
    "created_at": "2025-12-16T09:43:55.000000Z",
    "updated_at": "2025-12-16T09:44:06.000000Z",
    "schedule": {
      "id": "019b268b-ac04-7132-b521-014a82b4f5d5",
      "request_id": "019b268b-8160-722e-b3fb-750f7deb57fd",
      "counselor_id": "019af420-06ce-70e2-b804-fd6e2beeb198",
      "method": "chat",
      "schedule_date": "2025-12-25T00:00:00.000000Z",
      "time_slot": "14:00",
      "status": "pending",
      "confirmed_at": null,
      "created_at": "2025-12-16T09:44:06.000000Z",
      "updated_at": "2025-12-16T09:44:06.000000Z",
      "counselor": {
        "id": "019af420-06ce-70e2-b804-fd6e2beeb198",
        "nip": "198505152010022002",
        "name": "Pak Bima Sakti",
        "email": "bima.bk@sekolah.sch.id",
        "role": "bk",
        "jabatan": "Staf BK",
        "email_verified_at": "2025-12-06T14:45:30.000000Z",
        "created_at": "2025-12-06T14:45:30.000000Z",
        "updated_at": "2025-12-06T14:45:30.000000Z",
        "avatar_file_id": null,
        "phone_number": "",
        "is_available": true,
        "avatar_url": "http://api.teman-konseling.test/static/profile.png"
      }
    }
  },
  {
    "id": "019b26b8-d485-70d6-bcef-bca9204744c7",
    "student_id": "019b14a8-421f-73de-b074-1b21f66dbe5c",
    "title": "Saya takut ga lolos utbk",
    "description": "Jekekej rjrjjee ejeje ejejeusoxh eisuxjebekejennejej djdn",
    "urgency": "medium",
    "status": "scheduled",
    "created_at": "2025-12-16T10:33:25.000000Z",
    "updated_at": "2025-12-16T10:33:33.000000Z",
    "schedule": {
      "id": "019b26b8-f2db-7012-8a08-8e19aaa54777",
      "request_id": "019b26b8-d485-70d6-bcef-bca9204744c7",
      "counselor_id": "019af420-0535-7005-8188-872b3274c131",
      "method": "face-to-face",
      "schedule_date": "2025-12-25T00:00:00.000000Z",
      "time_slot": "14:00",
      "status": "pending",
      "confirmed_at": null,
      "created_at": "2025-12-16T10:33:33.000000Z",
      "updated_at": "2025-12-16T10:33:33.000000Z",
      "counselor": {
        "id": "019af420-0535-7005-8188-872b3274c131",
        "nip": "198001012005011001",
        "name": "Ibu Lia Permata",
        "email": "lia.bk@sekolah.sch.id",
        "role": "bk",
        "jabatan": "Koordinator BK",
        "email_verified_at": "2025-12-06T14:45:30.000000Z",
        "created_at": "2025-12-06T14:45:30.000000Z",
        "updated_at": "2025-12-13T04:34:16.000000Z",
        "avatar_file_id": null,
        "phone_number": "",
        "is_available": true,
        "avatar_url": "http://api.teman-konseling.test/static/profile.png"
      }
    }
  },
  {
    "id": "019b2b90-c2e6-7387-a6a9-435ca1439883",
    "student_id": "019b14a8-421f-73de-b074-1b21f66dbe5c",
    "title": "Takut sama masa depan pak",
    "description": "Bisa kesurupan jerome polin gak, sjhdbdhdhdbhxhxhdudhdbdjdnncncjfjcjchchchhhccjcjcncbfhdjjfjdjfjhfbf",
    "urgency": "high",
    "status": "scheduled",
    "created_at": "2025-12-17T09:07:46.000000Z",
    "updated_at": "2025-12-17T09:08:14.000000Z",
    "schedule": {
      "id": "019b2b91-3320-703d-b93b-7d66b05e4582",
      "request_id": "019b2b90-c2e6-7387-a6a9-435ca1439883",
      "counselor_id": "019af420-0535-7005-8188-872b3274c131",
      "method": "chat",
      "schedule_date": "2025-12-17T00:00:00.000000Z",
      "time_slot": "14:00",
      "status": "pending",
      "confirmed_at": null,
      "created_at": "2025-12-17T09:08:14.000000Z",
      "updated_at": "2025-12-17T09:08:14.000000Z",
      "counselor": {
        "id": "019af420-0535-7005-8188-872b3274c131",
        "nip": "198001012005011001",
        "name": "Ibu Lia Permata",
        "email": "lia.bk@sekolah.sch.id",
        "role": "bk",
        "jabatan": "Koordinator BK",
        "email_verified_at": "2025-12-06T14:45:30.000000Z",
        "created_at": "2025-12-06T14:45:30.000000Z",
        "updated_at": "2025-12-13T04:34:16.000000Z",
        "avatar_file_id": null,
        "phone_number": "",
        "is_available": true,
        "avatar_url": "http://api.teman-konseling.test/static/profile.png"
      }
    }
  }
];
const counselingRequests = ref(rawData);
const activeTab = ref('all');

// Stats dihitung secara dinamis
const stats = computed(() => {
  return {
    highUrgency: counselingRequests.value.filter(r => r.urgency === 'high').length,
    scheduled: counselingRequests.value.filter(r => r.status === 'scheduled').length,
    pending: counselingRequests.value.filter(r => r.status === 'pending').length,
  };
});

// Filter Tab
const filteredRequests = computed(() => {
  if (activeTab.value === 'all') return counselingRequests.value;
  return counselingRequests.value.filter(r => r.status === activeTab.value);
});

// Helper Warna & Format
function getUrgencyClass(urgency: string) {
  switch (urgency) {
    case 'high': return 'bg-red-100 text-red-700';
    case 'medium': return 'bg-orange-100 text-orange-700';
    default: return 'bg-primary-100 text-primary-700';
  }
}

function getStatusClass(status: string) {
  switch (status) {
    case 'scheduled': return 'bg-indigo-100 text-indigo-700';
    case 'pending': return 'bg-amber-100 text-amber-700';
    default: return 'bg-gray-100 text-gray-700';
  }
}

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('id-ID', {
    day: 'numeric', month: 'short', year: 'numeric'
  });
}
</script>
<template>
  <div class="grid grid-cols-12 gap-6">

    <div class="col-span-12 grid grid-cols-4 gap-6">

      <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="p-3 bg-yellow-100 rounded-full text-yellow-500">
            <Icon name="tabler:list-check" class="w-6 h-6" />
          </div>
          <div>
            <div class="text-3xl font-bold">24</div>
            <div class="text-sm text-gray-500">Completed Project</div>
          </div>
        </div>
        <div class="flex flex-col items-end text-green-500 text-sm font-medium">
          <Icon name="tabler:arrow-up" class="w-4 h-4" />
          1.3%
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="p-3 bg-orange-100 rounded-full text-orange-500">
            <Icon name="tabler:clipboard-list" class="w-6 h-6" />
          </div>
          <div>
            <div class="text-3xl font-bold">07</div>
            <div class="text-sm text-gray-500">Pending Project</div>
          </div>
        </div>
        <div class="flex flex-col items-end text-green-500 text-sm font-medium">
          <Icon name="tabler:arrow-up" class="w-4 h-4" />
          1.3%
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="p-3 bg-blue-100 rounded-full text-blue-500">
            <Icon name="tabler:currency-dollar" class="w-6 h-6" />
          </div>
          <div>
            <div class="text-3xl font-bold">$12,000</div>
            <div class="text-sm text-gray-500">Total Income</div>
          </div>
        </div>
        <div class="flex flex-col items-end text-green-500 text-sm font-medium">
          <Icon name="tabler:arrow-up" class="w-4 h-4" />
          1.3%
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="p-3 bg-red-100 rounded-full text-red-500">
            <Icon name="tabler:wallet" class="w-6 h-6" />
          </div>
          <div>
            <div class="text-3xl font-bold">$12,000</div>
            <div class="text-sm text-gray-500">Pending</div>
          </div>
        </div>
        <div class="flex flex-col items-end text-green-500 text-sm font-medium">
          <Icon name="tabler:arrow-up" class="w-4 h-4" />
          1.3%
        </div>
      </div>
    </div>

    <div class="col-span-8 space-y-6">

      <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">Project Summary</h3>
          <select class="px-3 py-1 border border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <option>All Project</option>
          </select>
        </div>

        <div class="flex space-x-4 border-b border-gray-100 mb-4">
          <button class="pb-2 text-sm font-semibold text-blue-600 border-b-2 border-blue-600">Ongoing</button>
          <button class="pb-2 text-sm font-medium text-gray-500 hover:text-gray-800">Negotiation</button>
          <button class="pb-2 text-sm font-medium text-gray-500 hover:text-gray-800">Completed</button>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project
                </th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project
                  NO</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress
                </th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount
                </th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service
                  Type</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">New Work Les DO</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">232323</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">12/12/2023</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="h-1 bg-green-500 rounded-full w-1/3"></div> <span
                    class="text-xs text-gray-500 mt-1 block">33%</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$45454.888</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Navy</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">You can Change the text
                  and all</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">232323</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">12/12/2023</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="h-1 bg-green-500 rounded-full w-3/5"></div> <span
                    class="text-xs text-gray-500 mt-1 block">55%</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$45454.888</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Navy</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">In
                    - progress</span>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Landing Page Design</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">232323</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">12/12/2023</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="h-1 bg-green-500 rounded-full w-1/8"></div> <span
                    class="text-xs text-gray-500 mt-1 block">12%</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$45454.888</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Navy</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Landing Page Design</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">232323</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">12/12/2023</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="h-1 bg-green-500 rounded-full w-1/8"></div> <span
                    class="text-xs text-gray-500 mt-1 block">12%</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$45454.888</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Navy</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                </td>
              </tr>
              <tr>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">Landing Page Design</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">232323</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">12/12/2023</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div class="h-1 bg-green-500 rounded-full w-1/8"></div> <span
                    class="text-xs text-gray-500 mt-1 block">12%</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$45454.888</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Navy</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">Income & Expense</h3>
          <select class="px-3 py-1 border border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <option>Yearly</option>
          </select>
        </div>

        <div class="flex items-center space-x-2 mb-4">
          <span class="text-4xl font-bold text-gray-900">$77,000</span>
          <span class="text-sm text-gray-500">/ 09 Projects</span>
        </div>

        <div class="h-64 bg-gray-50 flex items-center justify-center border rounded-lg">
          <span class="text-gray-400">Line Chart Placeholder (Income and Profit)</span>
        </div>

        <div class="flex justify-center space-x-6 mt-4">
          <div class="flex items-center text-sm font-medium text-green-500">
            <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
            Income
          </div>
          <div class="flex items-center text-sm font-medium text-yellow-500">
            <span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
            Profit
          </div>
        </div>
      </div>

    </div>

    <div class="col-span-4 space-y-6">

      <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">Awards</h3>
          <select class="px-3 py-1 border border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <option>Monthly</option>
          </select>
        </div>

        <div class="flex items-center space-x-4">
          <div class="w-32 h-32 shrink-0 bg-gray-50 rounded-full border flex items-center justify-center">
            <span class="text-xs text-gray-400">Donut</span>
          </div>

          <div class="space-y-2 grow">
            <div class="flex justify-between text-sm">
              <span class="flex items-center text-blue-600 font-medium"><span
                  class="inline-block w-2 h-2 rounded-full bg-blue-600 mr-2"></span>FY2022 Q1</span>
              <span>4101</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="flex items-center text-red-500 font-medium"><span
                  class="inline-block w-2 h-2 rounded-full bg-red-500 mr-2"></span>FY2022 Q2</span>
              <span>423</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="flex items-center text-yellow-500 font-medium"><span
                  class="inline-block w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>FY2022 Q3</span>
              <span>405</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="flex items-center text-sky-500 font-medium"><span
                  class="inline-block w-2 h-2 rounded-full bg-sky-500 mr-2"></span>FY2022 Q2</span>
              <span>90</span>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Over all Summary</h3>

        <div class="space-y-3">
          <div class="flex justify-between items-center text-sm">
            <span class="font-medium text-gray-700">Task</span>
            <div class="flex items-center space-x-2">
              <div class="h-2 w-20 bg-green-500 rounded-full"></div>
              <span class="font-bold text-gray-900">10</span>
            </div>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="font-medium text-gray-700">Project</span>
            <div class="flex items-center space-x-2">
              <div class="h-2 w-20 bg-lime-500 rounded-full"></div>
              <span class="font-bold text-gray-900">10</span>
            </div>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="font-medium text-gray-700">Client</span>
            <div class="flex items-center space-x-2">
              <div class="h-2 w-20 bg-pink-500 rounded-full"></div>
              <span class="font-bold text-gray-900">10</span>
            </div>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="font-medium text-gray-700">Revenue</span>
            <div class="flex items-center space-x-2">
              <div class="h-2 w-20 bg-red-500 rounded-full"></div>
              <span class="font-bold text-gray-900">10</span>
            </div>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="font-medium text-gray-700">Income</span>
            <div class="flex items-center space-x-2">
              <div class="h-2 w-20 bg-blue-500 rounded-full"></div>
              <span class="font-bold text-gray-900">10</span>
            </div>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="font-medium text-gray-700">Expense</span>
            <div class="flex items-center space-x-2">
              <div class="h-2 w-20 bg-violet-500 rounded-full"></div>
              <span class="font-bold text-gray-900">10</span>
            </div>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="font-medium text-gray-700">Leads</span>
            <div class="flex items-center space-x-2">
              <div class="h-2 w-20 bg-yellow-500 rounded-full"></div>
              <span class="font-bold text-gray-900">10</span>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>
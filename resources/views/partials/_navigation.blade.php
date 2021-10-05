<div class="mx-auto pb-4">
    <div class="flex justify-between items-center border-b-2 border-gray-100 py-2 md:justify-start md:space-x-10">
        <div class="flex justify-start lg:w-0 lg:flex-1 pl-4">
            <a href="{{ url('?view=user.dashboard') }}">
                <span class="sr-only">Workflow</span>
                <img class="h-8 w-auto sm:h-10" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="">
            </a>
        </div>
        <div class="md:hidden">
            <button type="button" class="bg-white rounded-md p-2 mr-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                <span class="sr-only">Open menu</span>
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <nav class="hidden md:flex space-x-10">
            <a href="{{ url('?view=contract.create') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">
                Nuevo contrato
            </a>
            <a href="#" class="text-base font-medium text-gray-500 hover:text-gray-900">
                Nuevo cliente
            </a>
            <a href="#" class="text-base font-medium text-gray-500 hover:text-gray-900">
                Informes
            </a>
        </nav>
        <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0 pr-4">
            <div class="mt-1 flex rounded-md shadow-sm">
                  <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                    <i class="fa fa-search"></i>
                  </span>
                <input type="text" name="quick-search" id="quick-search" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" placeholder="Busqueda rapida">
            </div>
        </div>
    </div>
</div>

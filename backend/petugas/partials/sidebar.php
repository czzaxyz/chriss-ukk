<!-- sidenav  -->
<aside
  class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
  
  <!-- Logo Section -->
  <div class="h-19.5">
    
    <a
      class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700"
      href="javascript:;"
      target="_blank">
      <img
        src="../../../templates-admin/build/assets/img/logo-ct.png"
        class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8"
        alt="main_logo" />
      <span
        class="ml-1 font-semibold transition-all duration-200 ease-nav-brand text-black"
        >Motor Rental</span
      >
    </a>
  </div>

  <hr class="my-0 mx-4 border-t border-slate-200">

  <div class="sidebar-wrapper">
    <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
      <ul class="flex flex-col pl-0 mb-0">
        
        <!-- Dashboard -->
        <li class="mt-0.5 w-full">
          <a
            class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors <?= ($page == 'dashboard') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'bg-white text-slate-700' ?>"
            href="../../pages/dashboard/index.php">
            <div
              class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5 <?= ($page == 'dashboard') ? 'bg-white/20' : 'bg-white shadow-soft-2xl' ?>">
              <i class="fas fa-tachometer-alt <?= ($page == 'dashboard') ? 'text-white' : 'text-slate-700' ?>" style="font-size: 1rem;"></i>
            </div>
            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
          </a>
        </li>

        <!-- Data Motor -->
        <li class="mt-0.5 w-full">
          <a
            class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors <?= ($page == 'motor') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'bg-white text-slate-700' ?>"
            href="../../pages/motor/index.php">
            <div
              class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5 <?= ($page == 'motor') ? 'bg-white/20' : 'bg-white shadow-soft-2xl' ?>">
              <i class="fas fa-motorcycle <?= ($page == 'motor') ? 'text-white' : 'text-slate-700' ?>" style="font-size: 1rem;"></i>
            </div>
            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Data Motor</span>
          </a>
        </li>

        <!-- Kategori -->
        <li class="mt-0.5 w-full">
          <a
            class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors <?= ($page == 'kategori') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'bg-white text-slate-700' ?>"
            href="../../pages/kategori/index.php">
            <div
              class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5 <?= ($page == 'kategori') ? 'bg-white/20' : 'bg-white shadow-soft-2xl' ?>">
              <i class="fas fa-folder <?= ($page == 'kategori') ? 'text-white' : 'text-slate-700' ?>" style="font-size: 1rem;"></i>
            </div>
            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Kategori</span>
          </a>
        </li>

        <!-- Peminjaman -->
        <li class="mt-0.5 w-full">
          <a
            class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors <?= ($page == 'peminjaman') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'bg-white text-slate-700' ?>"
            href="../../pages/peminjaman/index.php">
            <div
              class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5 <?= ($page == 'peminjaman') ? 'bg-white/20' : 'bg-white shadow-soft-2xl' ?>">
              <i class="fas fa-handshake <?= ($page == 'peminjaman') ? 'text-white' : 'text-slate-700' ?>" style="font-size: 1rem;"></i>
            </div>
            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Peminjaman</span>
          </a>
        </li>

        <!-- Pengembalian -->
        <li class="mt-0.5 w-full">
          <a
            class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors <?= ($page == 'pengembalian') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'bg-white text-slate-700' ?>"
            href="../../pages/pengembalian/index.php">
            <div
              class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5 <?= ($page == 'pengembalian') ? 'bg-white/20' : 'bg-white shadow-soft-2xl' ?>">
              <i class="fas fa-undo-alt <?= ($page == 'pengembalian') ? 'text-white' : 'text-slate-700' ?>" style="font-size: 1rem;"></i>
            </div>
            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Pengembalian</span>
          </a>
        </li>

        <!-- Data User -->
        <li class="mt-0.5 w-full">
          <a
            class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors <?= ($page == 'contact') ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'bg-white text-slate-700' ?>"
            href="../../pages/contact/index.php">
            <div
              class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5 <?= ($page == 'contact') ? 'bg-white/20' : 'bg-white shadow-soft-2xl' ?>">
              <i class="fas fa-address-book <?= ($page == 'contact') ? 'text-white' : 'text-slate-700' ?>" style="font-size: 1rem;"></i>
            </div>
            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Contact</span>
          </a>
        </li>

        <!-- Akun -->
        <li class="w-full mt-4">
          <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60 text-slate-500">
            AKUN
          </h6>
        </li>

        <!-- Logout -->
        <li class="mt-0.5 w-full">
          <a
            class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors bg-white text-slate-700"
            href="../../../action/auth/logout.php">
            <div
              class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-soft-2xl bg-center stroke-0 text-center xl:p-2.5">
              <i class="fas fa-sign-out-alt text-slate-700" style="font-size: 1rem;"></i>
            </div>
            <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Logout</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</aside>
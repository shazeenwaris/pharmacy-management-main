<div style="background: linear-gradient(135deg, #00707A 0%, #00707A 100%); border-radius: 0.75rem; padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; color: white; margin-bottom: 1rem;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 3rem; height: 3rem; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
        </div>
        <div>
            <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700;">Expense Categories</h2>
            <p style="margin: 0.15rem 0 0 0; font-size: 0.85rem; opacity: 0.85;">Organize and manage your expense categories</p>
        </div>
    </div>

    <a href="{{ \App\Filament\Resources\ExpenseCategories\ExpenseCategoryResource::getUrl('create') }}"
    style="background: white; color: #00707A; padding: 0.6rem 1.1rem; border-radius: 0.5rem; text-decoration: none; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(0,0,0,.12); transition: transform .15s ease, box-shadow .15s ease;">
        + New Category
    </a>
</div>
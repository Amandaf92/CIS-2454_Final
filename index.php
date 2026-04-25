<!DOCTYPE html>
<html>
<head>
    <title>Shopping List App</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
</head>

<body class="bg-light">
<div id="app"></div>

<script type="text/babel">
const { useState, useEffect } = React;

function App() {
    const [stores, setStores] = useState([]);
    const [selectedStore, setSelectedStore] = useState(null);
    const [items, setItems] = useState([]);
    const [newStore, setNewStore] = useState("");
    const [newItem, setNewItem] = useState("");

    const [editingItemId, setEditingItemId] = useState(null);
    const [editName, setEditName] = useState("");
    const [editQty, setEditQty] = useState(1);

    const fetchStores = async () => {
        const res = await fetch("api/stores.php");
        setStores(await res.json());
    };

    const fetchItems = async (storeId) => {
        const res = await fetch(`api/items.php?store_id=${storeId}`);
        setItems(await res.json());
    };

    useEffect(() => { fetchStores(); }, []);

    const addStore = async () => {
        if (!newStore.trim()) return;

        await fetch("api/stores.php", {
            method: "POST",
            body: JSON.stringify({ name: newStore })
        });

        setNewStore("");
        fetchStores();
    };

    const deleteStore = async (id) => {
        await fetch(`api/stores.php?id=${id}`, { method: "DELETE" });
        setSelectedStore(null);
        fetchStores();
    };

    const addItem = async () => {
        if (!newItem.trim()) return;

        await fetch("api/items.php", {
            method: "POST",
            body: JSON.stringify({
                store_id: selectedStore.id,
                name: newItem,
                quantity: 1
            })
        });

        setNewItem("");
        fetchItems(selectedStore.id);
    };

    const deleteItem = async (id) => {
        await fetch(`api/items.php?id=${id}`, { method: "DELETE" });
        fetchItems(selectedStore.id);
    };

    const toggleItem = async (item) => {
        await fetch("api/items.php", {
            method: "PUT",
            body: JSON.stringify({
                id: item.id,
                checked: item.checked ? 0 : 1,
                name: item.name,
                quantity: item.quantity
            })
        });
        fetchItems(selectedStore.id);
    };

    const startEdit = (item) => {
        setEditingItemId(item.id);
        setEditName(item.name);
        setEditQty(item.quantity);
    };

    const saveEdit = async (item) => {
        await fetch("api/items.php", {
            method: "PUT",
            body: JSON.stringify({
                id: item.id,
                checked: item.checked,
                name: editName,
                quantity: editQty
            })
        });

        setEditingItemId(null);
        fetchItems(selectedStore.id);
    };

    return (
        <div className="container mt-4">
            <h2>Shopping Lists</h2>

            <div className="mb-3 d-flex gap-2">
                <input className="form-control"
                    value={newStore}
                    onChange={e => setNewStore(e.target.value)}
                    placeholder="New Store" />
                <button className="btn btn-primary" onClick={addStore}>
                    Add
                </button>
            </div>

            <ul className="list-group">
                {stores.map(store => (
                    <li key={store.id}
                        className="list-group-item d-flex justify-content-between align-items-center">

                        <span
                            onClick={() => {
                                setSelectedStore(store);
                                fetchItems(store.id);
                            }}
                            style={{ cursor: "pointer" }}>
                            {store.name}
                        </span>

                        <button className="btn btn-sm btn-danger"
                            onClick={() => deleteStore(store.id)}>
                            Delete
                        </button>
                    </li>
                ))}
            </ul>

            {selectedStore && (
                <div className="mt-4">
                    <h4>{selectedStore.name} Items</h4>

                    <div className="d-flex gap-2">
                        <input className="form-control"
                            value={newItem}
                            onChange={e => setNewItem(e.target.value)}
                            placeholder="New Item" />
                        <button className="btn btn-success" onClick={addItem}>
                            Add
                        </button>
                    </div>

                    <ul className="list-group mt-3">
                        {items.map(item => (
                            <li key={item.id}
                                className="list-group-item d-flex justify-content-between align-items-center">

                                {editingItemId === item.id ? (
                                    <div className="d-flex w-100">
                                        <input
                                            className="form-control me-2"
                                            value={editName}
                                            onChange={e => setEditName(e.target.value)}
                                        />
                                        <input
                                            type="number"
                                            className="form-control me-2"
                                            style={{ width: "80px" }}
                                            value={editQty}
                                            onChange={e => setEditQty(e.target.value)}
                                        />

                                        <button className="btn btn-sm btn-success me-1"
                                            onClick={() => saveEdit(item)}>
                                            Save
                                        </button>

                                        <button className="btn btn-sm btn-secondary"
                                            onClick={() => setEditingItemId(null)}>
                                            Cancel
                                        </button>
                                    </div>
                                ) : (
                                    <div className="d-flex justify-contentbetween w-100 align-items-center">
                                        <span
                                            style={{
                                                cursor: "pointer"
                                            }}
                                            onClick={() => toggleItem(item)}>
                                            {item.name }
                                        </span>

                                        <div>
                                            <button className="btn btn-sm btn-warning me-1"
                                                onClick={() => startEdit(item)}>
                                                Edit
                                            </button>

                                            <button className="btn btn-sm btn-danger"
                                                onClick={() => deleteItem(item.id)}>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                )}
                            </li>
                        ))}
                    </ul>
                </div>
            )}
        </div>
    );
}

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
</script>
</body>
</html>
<?php

/* @var $this \ApCode\Executor\PhpFileExecutor */

use Project\IngredientType;
use Project\IngredientTypeRepository;

$ingredients = $this->param('ingredients');

RequireLib('vue3');

Layout()->append('body.js.code', file_get_contents(__dir('view_controller.js')));

Layout()->startGrab('body.content.end');
?>
<script type="text/html" id="solutionViewForm">
  <table class="table table-bordered w-auto">
    <caption class="caption-top">Типы ингредиентов</caption>
    <thead>
      <tr>
        <th>Код</th>
        <th>Наименование</th>
        <th>Количество</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="type in types">
        <td>{{type.code}}</td>
        <td>{{type.title}}</td>
        <td>
          <div class="input-group">
            <button class="btn btn-default" type="button" @click="removeIngredient(type.code)"><i class="bi bi-dash-lg"></i></button>
            <input :title="ingredientTypes[type.code].error" readonly type="text" size="3" v-model="ingredientTypes[type.code].number" class="form-control" :class="{'is-invalid': ingredientTypes[type.code].error}">
            <button class="btn btn-default" type="button" @click="addIngredient(type.code)"><i class="bi bi-plus-lg"></i></button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <form class="row row-cols-lg-auto g-3 align-items-end" @submit.prevent="submit">
    <div class="col-12">
      <label for="ingredients" class="form-label">Входящая строка ингредиентов</label>
      <input type="text" :disabled="loading" class="form-control font-monospace" id="ingredients" v-model="ingredients" placeholder="Например, dcciii">
    </div>
    <div class="col-12">
      <label class="form-label" for="calcType">Калькулятор</label>
      <select class="form-select" id="calcType" v-model="calcType" :disabled="loading">
        <option value="mysql">Выборка MySQL</option>
        <option value="oop">ООП</option>
      </select>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-default" :disabled="loading">
        <div v-if="loading" class="spinner-border spinner-border-sm text-primary" role="status">
          <span class="visually-hidden">Загрузка...</span>
        </div>
        <i v-if="!loading" class="bi bi-check-lg text-success"></i>
        Рассчитать комбинации
      </button>
    </div>
  </form>

  <div v-if="result">
    <h3 class="mt-3 mb-2">Комбинации ингредиентов ({{result.length}})</h3>

    <ul class="nav nav-tabs mb-2">
      <li class="nav-item">
        <a class="nav-link" :class="{active: view === 'table'}" href="#" @click.prevent="view='table'">Таблица</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" :class="{active: view === 'json'}" href="#" @click.prevent="view='json'">JSON</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" :class="{active: view === 'stats'}" href="#" @click.prevent="view='stats'">Статистика</a>
      </li>
    </ul>

    <div v-if="view === 'table'">
      <div v-if="result.error" class="alert alert-danger">
        {{result.error}}
      </div>

      <table v-if="!result.error" class="table">
        <thead>
          <tr>
            <th>№</th>
            <th>Составляющие</th>
            <th>Цена</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, i) in result">
            <td>{{i + 1}}</td>
            <td>{{row.products.map(r => `${r.value} (${r.type})`).join(', ')}}</td>
            <td>{{row.price}}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="view === 'json'">
      <pre><code>{{result}}</code></pre>
    </div>

    <div v-if="view === 'stats'">
      <pre><code>{{stats && stats.join('\n')}}</code></pre>
    </div>
  </div>

</script>
<?php
Layout()->endGrab();

$types = array_map(static fn(IngredientType $o) => $o->toArray(), IngredientTypeRepository::findMany([]));
?>
<solution-view-form
  :types="<?=Html(json_encode_array($types))?>"
  init-ingredients="<?=Html($ingredients)?>"
>
  <div class="text-center text-body-secondary">
    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
    Загрузка...
  </div>
</solution-view-form>

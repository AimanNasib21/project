<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Categories;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Mail\Markdown;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $modelLabel = 'Post';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create a Post')
                ->description('Create post di sini')
                ->collapsible()
                ->schema([
                TextInput::make('title')->required(),
                TextInput::make('slug')->required(),
                Select::make('category_id')
                ->label('Category')
                ->options(Categories::all()->pluck('name','id')),
                TagsInput::make('tags')->required(),

                MarkdownEditor::make('content')->required()->columnSpan(2),
                Toggle::make('published')->required(),
                ])->columns(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('slug'),
                TextColumn::make('category.name'),
                TextColumn::make('tags'),
                ToggleColumn::make('published'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

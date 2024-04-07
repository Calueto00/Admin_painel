<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create')
                ->description('Create a post over here')
                ->schema([

                    TextInput::make('title')->rules('min:3|max:15')->required(),
                    ColorPicker::make('color')->required(),
                    TextInput::make('slug')->unique()->required(),
                ])->columnSpan(1)->columns(1),

                Section::make()->schema([
                    Section::make()->schema([
                        Select::make('category_id')
                    ->relationship('category','name')
                    ->searchable()
                    ->label('Category')->required(),
                    Checkbox::make('published')->required(),
                    ])->columns(2),
                    FileUpload::make('thumbnail')->label('Image')->disk('public')->directory('uploads'),


                ])->columnSpan(1)->columns(1),

                //Section::make('Authors')->schema([
                //    Select::make('Authors')
                //    ->label('Co Authors')
                //    ->relationship('authors','name')
               // ]),

                MarkdownEditor::make('content')->required()->columnSpan(2),



            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->toggleable(),
                TextColumn::make('title')->searchable()->label('Title')->toggleable(),
                TextColumn::make('slug')->label('Slug')->toggleable(),
                ColorColumn::make('color')->label('Color')->toggleable(),
                TextColumn::make('category.name')->searchable()->toggleable(),
                ImageColumn::make('thumbnail')->label('Image')->toggleable(),
                CheckboxColumn::make('published')->label('Published')->toggleable(),
                TextColumn::make('created_at')->label('Published On')->date()->toggleable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AuthorsRelationManager::class
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
